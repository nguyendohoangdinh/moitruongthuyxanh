<?php

namespace barrelstrength\sproutbaseemail\services;

use barrelstrength\sproutbaseemail\base\NotificationEvent;
use barrelstrength\sproutbaseemail\elements\NotificationEmail;
use barrelstrength\sproutbaseemail\events\NotificationEmailEvent;
use barrelstrength\sproutbaseemail\events\SendNotificationEmailEvent;
use barrelstrength\sproutbaseemail\SproutBaseEmail;
use Craft;
use craft\base\Component;
use craft\helpers\ArrayHelper;
use craft\helpers\Json;
use Throwable;
use yii\base\Event;

/**
 * Class NotificationEmailEvents
 *
 * @package barrelstrength\sproutbaseemail\services
 *
 * @property array    $notificationEmailEventTypes
 * @property Callable $dynamicEventHandler
 */
class NotificationEmailEvents extends Component
{
    /**
     * @event NotificationEmailEvent Event is triggered when the Craft App initializes
     */
    const EVENT_REGISTER_EMAIL_EVENT_TYPES = 'registerSproutNotificationEmailEvents';

    /**
     * @event SendNotificationEmailEvent Event is triggered when a Notification Email is sent
     */
    const EVENT_SEND_NOTIFICATION_EMAIL = 'onSendNotificationEmail';

    /**
     * @var Callable[] Events that notifications have subscribed to
     */
    protected $registeredEvents = [];

    /**
     * Registers an event listener to be trigger dynamically
     *
     * @param string   $eventId
     * @param Callable $callback
     */
    public function registerEvent($eventId, $callback)
    {
        $this->registeredEvents[$eventId] = $callback;
    }

    /**
     * Returns a callable for the given event
     *
     * @param string $eventId
     *
     * @return Callable
     */
    public function getRegisteredEvent($eventId): callable
    {
        return $this->registeredEvents[$eventId] ?? static function() {
            };
    }


    /**
     * Returns all the available Notification Email Event Types
     *
     * @return array
     */
    public function getNotificationEmailEventTypes(): array
    {
        $event = new NotificationEmailEvent([
            'events' => []
        ]);

        $this->trigger(self::EVENT_REGISTER_EMAIL_EVENT_TYPES, $event);

        return $event->events;
    }

    /**
     * Registers a closure for each event we should be listening for via Event::on
     *
     * @note
     * 1. Get all Notification Email Event Types that we need to listen for
     * 2. Register an anonymous function to $this->registeredEvents for each event using Event::on
     * 3. Call our anonymous function when the event is triggered
     *
     * @return bool
     */
    public function registerNotificationEmailEventHandlers(): bool
    {
        $self = $this;
        $notificationEmailEventTypes = $this->getNotificationEmailEventTypes();

        if (empty($notificationEmailEventTypes)) {
            return false;
        }

        foreach ($notificationEmailEventTypes as $notificationEmailEventClassName) {

            // Create an instance of this event
            $notificationEmailEvent = new $notificationEmailEventClassName();

            if ($notificationEmailEvent instanceof NotificationEvent) {
                // Register our event
                $self->registerEvent($notificationEmailEventClassName, $self->getDynamicEventHandler());

                if (Craft::$app->getRequest()->getIsConsoleRequest() == true) {
                    continue;
                }

                $eventClassName = $notificationEmailEvent->getEventClassName();
                $event = $notificationEmailEvent->getEventName();
                /** @noinspection PhpUnusedLocalVariableInspection */
                $eventHandlerClassName = $notificationEmailEvent->getEventHandlerClassName();

                Event::on($eventClassName, $event, static function($eventHandlerClassName)
                use ($self, $notificationEmailEventClassName, $notificationEmailEvent) {
                    return call_user_func($self->getRegisteredEvent($notificationEmailEventClassName),
                        $notificationEmailEventClassName, $eventHandlerClassName, $notificationEmailEvent);
                });
            }
        }

        return true;
    }

    /**
     * Returns the callable/closure that will handle dynamic event delegation when
     * a registered Event is called.
     *
     * This closure is necessary to avoid creating a method for every possible event
     * This closure allows us to avoid having to register for every possible event via Event::on
     * This closure allows us to know the current event being triggered dynamically
     *
     * @return Callable
     * @example - An overview of how this works. When the sproutemail is initialized...
     *
     * 1. We check which events we need to register for via Event::on
     * 2. We register an anonymous function as the handler
     * 3. This closure gets called with the name of the event and the event itself
     * 4. This closure executes as real event handler for the triggered event
     *
     */
    public function getDynamicEventHandler(): callable
    {
        $self = $this;

        return static function($notificationEmailEventClassName, Event $event, NotificationEvent $eventHandlerClass) use ($self) {
            return $self->handleDynamicEvent($notificationEmailEventClassName, $event, $eventHandlerClass);
        };
    }

    /**
     * This method hands things off to Sprout Email when a Notification Event we registered gets triggered.
     *
     * @param                       $notificationEmailEventClassName
     * @param Event                 $event
     * @param NotificationEvent     $eventHandlerClass
     *
     * @return bool
     * @throws Throwable
     */
    public function handleDynamicEvent($notificationEmailEventClassName, Event $event, NotificationEvent $eventHandlerClass): bool
    {
        $request = Craft::$app->getRequest();

        // Only handle notifications on web requests
        if ($request->getIsConsoleRequest()) {
            return false;
        }

        // Don't process Notification Events when migrations are running
        // Code taken from Web Application _processUpdateLogic
        if ($request->getIsActionRequest()) {
            $actionSegments = $request->getActionSegments();
            if (
                ArrayHelper::firstValue($actionSegments) === 'updater' ||
                $actionSegments === ['app', 'migrate'] ||
                $actionSegments === ['pluginstore', 'install', 'migrate']
            ) {
                return false;
            }
        }

        Craft::info(Craft::t('sprout-base-email', 'A Notification Event has been triggered: {eventName}', [
            'eventName' => $eventHandlerClass->getName()
        ]));

        // Get all Notification Emails that match this Notification Event
        $notificationEmails = SproutBaseEmail::$app->notifications->getAllNotificationEmails($notificationEmailEventClassName);

        if ($notificationEmails) {

            /** @var NotificationEmail $notificationEmail */
            foreach ($notificationEmails as $notificationEmail) {

                // Add the Notification Event settings to the $eventHandlerClass
                $settings = Json::decode($notificationEmail->settings);

                /** @var NotificationEvent $eventHandlerClass */
                $eventHandlerClass = new $eventHandlerClass($settings);
                $eventHandlerClass->notificationEmail = $notificationEmail;
                $eventHandlerClass->event = $event;

                if (!$eventHandlerClass->validate()) {
                    Craft::error($eventHandlerClass->getName().' event does not validate: '.json_encode($eventHandlerClass->getErrors()), __METHOD__);
                    continue;
                }

                $object = $eventHandlerClass->getEventObject();
                $notificationEmail->setEventObject($object);

                // Don't send emails for disabled notification email entries.
                if (!$notificationEmail->isReady() OR
                    !$notificationEmail->sendRuleIsTrue()) {
                    continue;
                }

                SproutBaseEmail::$app->notifications->sendNotificationViaMailer($notificationEmail);

                $sendNotificationEmailEvent = new SendNotificationEmailEvent([
                    'event' => $event,
                    'notificationEmail' => $notificationEmail,
                ]);

                $this->trigger(self::EVENT_SEND_NOTIFICATION_EMAIL, $sendNotificationEmailEvent);
            }
        }

        return true;
    }

    /**
     * Returns a single notification event
     *
     * @param string $type The return value of the event getId()
     * @param mixed  $default
     *
     * @return NotificationEvent
     */
    public function getEventById($type, $default = null): NotificationEvent
    {
        $notificationEmailEventTypes = $this->getNotificationEmailEventTypes();

        foreach ($notificationEmailEventTypes as $notificationEmailEventClass) {
            if ($type === $notificationEmailEventClass) {
                return new $notificationEmailEventClass();
            }
        }

        return $default;
    }

    /**
     * @param NotificationEmail $notificationEmail
     *
     * @return NotificationEvent|null
     */
    public function getEvent(NotificationEmail $notificationEmail)
    {
        $notificationEmailEventTypes = $this->getNotificationEmailEventTypes();

        foreach ($notificationEmailEventTypes as $notificationEmailEventClass) {
            if ($notificationEmail->eventId === $notificationEmailEventClass) {
                $settings = Json::decode($notificationEmail->settings);

                return new $notificationEmailEventClass($settings);
            }
        }

        return null;
    }

    /**
     * Returns list of events for an Event dropdown and initializes the current selected event with any existing settings
     *
     * @param NotificationEmail $notificationEmail
     *
     * @return array
     */
    public function getNotificationEmailEvents(NotificationEmail $notificationEmail): array
    {
        $notificationEmailEventTypes = $this->getNotificationEmailEventTypes();

        $events = [];

        if (!empty($notificationEmailEventTypes)) {
            foreach ($notificationEmailEventTypes as $notificationEmailEventClass) {

                $settings = Json::decode($notificationEmail->settings);
                if ($notificationEmailEventClass === $notificationEmail->eventId) {
                    // If the Event matches are current selected event, initialize the NotificationEvent class with the Event settings
                    $event = new $notificationEmailEventClass($settings);
                } else {
                    $event = new $notificationEmailEventClass();
                }

                $events[$notificationEmailEventClass] = $event;
            }
        }

        uasort($events, static function($a, $b) {
            /**
             * @var $a NotificationEvent
             * @var $b NotificationEvent
             */
            return $a->getName() <=> $b->getName();
        });

        return $events;
    }

    /**
     * Returns events with a given plugin ID
     *
     * @param NotificationEmail $notificationEmail
     * @param string            $viewContext
     *
     * @return array
     */
    public function getNotificationEmailEventsByViewContext(NotificationEmail $notificationEmail, string $viewContext): array
    {
        $events = $this->getNotificationEmailEvents($notificationEmail);

        foreach ($events as $key => $event) {
            if ($viewContext !== $event->viewContext) {
                unset($events[$key]);
            }
        }

        return $events;
    }
}
