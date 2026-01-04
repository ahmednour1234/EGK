<?php

namespace App\Services;

use App\Models\Package;
use App\Models\TravelerTicket;
use Carbon\Carbon;

class TicketPackageMatcher
{
    // Scoring weights
    private const WEIGHT_PICKUP_CITY = 20;
    private const WEIGHT_DELIVERY_CITY = 20;
    private const WEIGHT_RECEIVER_MOBILE = 15;
    private const WEIGHT_RECEIVER_NAME = 10;
    private const WEIGHT_ADDRESS_KEYWORDS = 10;
    private const WEIGHT_DATE_PROXIMITY = 15;

    /**
     * Calculate match score between a package and a traveler ticket.
     *
     * @param Package $package
     * @param TravelerTicket $ticket
     * @return float Match score from 0 to 100
     */
    public function match(Package $package, TravelerTicket $ticket): float
    {
        // Hard requirements - if not met, return 0
        if (!$this->checkCompliance($package)) {
            return 0;
        }

        if (!$this->checkStatusCompatibility($package, $ticket)) {
            return 0;
        }

        $score = 0;

        // Pickup city match
        $score += $this->scorePickupCityMatch($package, $ticket);

        // Delivery city match
        $score += $this->scoreDeliveryCityMatch($package, $ticket);

        // Receiver mobile exact match
        $score += $this->scoreReceiverMobileMatch($package, $ticket);

        // Receiver name similarity
        $score += $this->scoreReceiverNameSimilarity($package, $ticket);

        // Address keywords match
        $score += $this->scoreAddressKeywordsMatch($package, $ticket);

        // Date proximity
        $score += $this->scoreDateProximity($package, $ticket);

        // Cap at 100
        return min(100, $score);
    }

    /**
     * Check if package compliance is confirmed (hard requirement).
     */
    private function checkCompliance(Package $package): bool
    {
        return $package->compliance_confirmed === true;
    }

    /**
     * Check status compatibility (hard requirement).
     */
    private function checkStatusCompatibility(Package $package, TravelerTicket $ticket): bool
    {
        // Package must be in matchable states
        $matchablePackageStatuses = ['approved', 'paid'];
        if (!in_array($package->status, $matchablePackageStatuses)) {
            return false;
        }

        // Ticket must be active
        if ($ticket->status !== 'active') {
            return false;
        }

        return true;
    }

    /**
     * Score pickup city match.
     */
    private function scorePickupCityMatch(Package $package, TravelerTicket $ticket): float
    {
        if (empty($package->pickup_city) || empty($ticket->from_city)) {
            return 0;
        }

        // Case-insensitive comparison
        if (strtolower(trim($package->pickup_city)) === strtolower(trim($ticket->from_city))) {
            return self::WEIGHT_PICKUP_CITY;
        }

        return 0;
    }

    /**
     * Score delivery city match.
     */
    private function scoreDeliveryCityMatch(Package $package, TravelerTicket $ticket): float
    {
        if (empty($package->delivery_city) || empty($ticket->to_city)) {
            return 0;
        }

        // Case-insensitive comparison
        if (strtolower(trim($package->delivery_city)) === strtolower(trim($ticket->to_city))) {
            return self::WEIGHT_DELIVERY_CITY;
        }

        return 0;
    }

    /**
     * Score receiver mobile exact match (normalized).
     */
    private function scoreReceiverMobileMatch(Package $package, TravelerTicket $ticket): float
    {
        // Note: TravelerTicket doesn't have receiver_mobile, so this factor is not applicable
        // This could be used if matching against another package or if ticket has receiver info
        // For now, return 0 as it's not applicable
        return 0;
    }

    /**
     * Score receiver name similarity.
     */
    private function scoreReceiverNameSimilarity(Package $package, TravelerTicket $ticket): float
    {
        // Note: TravelerTicket doesn't have receiver_name, so this factor is not applicable
        // This could be used if matching against another package or if ticket has receiver info
        // For now, return 0 as it's not applicable
        return 0;
    }

    /**
     * Score address keywords match.
     */
    private function scoreAddressKeywordsMatch(Package $package, TravelerTicket $ticket): float
    {
        if (empty($package->pickup_full_address) || empty($ticket->full_address)) {
            return 0;
        }

        // Extract keywords (words) from addresses
        $packageWords = $this->extractKeywords($package->pickup_full_address);
        $ticketWords = $this->extractKeywords($ticket->full_address);

        if (empty($packageWords) || empty($ticketWords)) {
            return 0;
        }

        // Count matching words
        $matchingWords = count(array_intersect($packageWords, $ticketWords));
        $totalWords = count(array_unique(array_merge($packageWords, $ticketWords)));

        if ($totalWords === 0) {
            return 0;
        }

        // Calculate similarity ratio
        $similarity = $matchingWords / $totalWords;

        return $similarity * self::WEIGHT_ADDRESS_KEYWORDS;
    }

    /**
     * Score date proximity.
     */
    private function scoreDateProximity(Package $package, TravelerTicket $ticket): float
    {
        if (!$package->pickup_date || !$ticket->departure_date) {
            return 0;
        }

        $packageDate = $package->pickup_date instanceof Carbon 
            ? $package->pickup_date 
            : Carbon::parse($package->pickup_date);
        
        $ticketDate = $ticket->departure_date instanceof Carbon 
            ? $ticket->departure_date 
            : Carbon::parse($ticket->departure_date);

        // Calculate days difference
        $daysDiff = abs($packageDate->diffInDays($ticketDate));

        // Score decreases as days difference increases
        // Perfect match (0 days) = full score
        // 7+ days difference = 0 score
        if ($daysDiff >= 7) {
            return 0;
        }

        // Linear decrease: 0 days = 100%, 7 days = 0%
        $similarity = 1 - ($daysDiff / 7);

        return $similarity * self::WEIGHT_DATE_PROXIMITY;
    }

    /**
     * Normalize phone number by removing all non-digit characters.
     */
    public function normalizePhone(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }

    /**
     * Calculate name similarity using similar_text (returns 0-1).
     */
    public function calculateNameSimilarity(string $name1, string $name2): float
    {
        if (empty($name1) || empty($name2)) {
            return 0;
        }

        $similarity = 0;
        similar_text(
            strtolower(trim($name1)), 
            strtolower(trim($name2)), 
            $similarity
        );

        return $similarity / 100; // Convert percentage to 0-1
    }

    /**
     * Extract keywords (words) from a string.
     */
    private function extractKeywords(string $text): array
    {
        // Convert to lowercase and extract words (alphanumeric)
        $words = preg_split('/\s+/', strtolower(trim($text)));
        
        // Filter out very short words (less than 2 characters) and common words
        $commonWords = ['the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'from'];
        
        $keywords = array_filter($words, function($word) use ($commonWords) {
            $cleaned = preg_replace('/[^a-z0-9]/', '', $word);
            return strlen($cleaned) >= 2 && !in_array($cleaned, $commonWords);
        });

        return array_values(array_unique($keywords));
    }
}

