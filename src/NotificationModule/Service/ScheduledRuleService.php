<?php
/**
 * Copyright © 2025 Enrique Pascalin <erparom@gmail.com>
 * This source code is protected under international copyright law.
 * All rights reserved. No warranty, explicit or implicit, provided.
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * This file is confidential and only available to authorized individuals with the
 * permission of the copyright holders.  If you encounter this file and do not have
 * permission, please contact the copyright holders and delete this file.
 *
 * @author Enrique Pascalin, Erparom Technologies
 *
 * @version 1.0.0
 *
 * @since 2025-06-01
 *
 * @license license.md
 */

declare(strict_types=1);

namespace App\NotificationModule\Service;

use App\NotificationModule\Entity\ScheduleRule;
use App\NotificationModule\ValueObject\RecurrencePattern;
use Cron\CronExpression;
use Webmozart\Assert\Assert;
use RRule\RRule;
use RRule\RSet;

/**
 * Service for generating execution triggers based on complex schedule rules.
 */
final class ScheduledRuleService
{
    /**
     * Generates trigger dates for a schedule rule
     *
     * @param ScheduleRule $rule
     * @param \DateTimeInterface $start
     * @param \DateTimeInterface $end
     * @return \DateTimeImmutable[]
     *
     * @throws \InvalidArgumentException
     */
    public function generateTriggers(
        ScheduleRule $rule,
        \DateTimeInterface $start,
        \DateTimeInterface $end
    ): array {
        Assert::lessThanEq($start, $end, 'Start date must be before end date');
        
        $triggers = [];
        $timezone = new \DateTimeZone($rule->getTimezone());
        
        // Handle cron-based rules
        if ($rule->getCronExpression()) {
            $cron = new CronExpression($rule->getCronExpression());
            $current = clone $start;
            
            while ($current < $end) {
                $nextRun = $cron->getNextRunDate($current);
                if ($nextRun > $end) break;
                
                if ($this->isValidDate($nextRun, $rule)) {
                    $triggers[] = \DateTimeImmutable::createFromMutable($nextRun)->setTimezone($timezone);
                }
                
                $current = $nextRun;
            }
        }
        
        // Handle RRULE-based recurrence
        if ($rule->getRecurrenceRule()) {
            $rset = new RSet($rule->getRecurrenceRule(), $rule->getStartDate()->format('Ymd\THis'));
            foreach ($rset->getOccurrencesBetween($start, $end) as $occurrence) {
                if ($this->isValidDate($occurrence, $rule)) {
                    $triggers[] = \DateTimeImmutable::createFromMutable($occurrence)->setTimezone($timezone);
                }
            }
        }
        
        return $triggers;
    }

    /**
     * Validates if date passes all rule constraints
     */
    private function isValidDate(\DateTimeInterface $date, ScheduleRule $rule): bool
    {
        // Check against excluded dates
        foreach ($rule->getExcludedDates() as $excluded) {
            if ($date->format('Y-m-d') === $excluded->format('Y-m-d')) {
                return false;
            }
        }
        
        // Check date range
        if ($rule->getStartDate() && $date < $rule->getStartDate()) {
            return false;
        }
        
        if ($rule->getEndDate() && $date > $rule->getEndDate()) {
            return false;
        }
        
        return true;
    }
}