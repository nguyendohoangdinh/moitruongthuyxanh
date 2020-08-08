<?php

namespace barrelstrength\sproutbasefields\helpers;

use CommerceGuys\Addressing\Country\CountryRepository;

/**
 * This class is only necessary because the availableLocales that
 * access below are a protected method in the Country Repository class
 *
 * @package barrelstrength\sproutbasefields\helpers
 */
class CountryRepositoryHelper extends CountryRepository
{
    /**
     * Helper method to retrieve protected property on parent class
     *
     * @return array
     */
    public function getAvailableLocales(): array
    {
        return $this->availableLocales;
    }
}