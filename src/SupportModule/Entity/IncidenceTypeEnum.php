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

namespace App\SupportModule\Entity;

use Symfony\Contracts\Translation\TranslatableInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

enum IncidenceTypeEnum: int implements TranslatableInterface
{
    case BUG = 0;                          // Bug
    case CHANGE_REQUEST = 1;               // Change request
    case OTHER = 2;                        // Other
    case INCIDENT = 3;                     // Incident
    case SERVICE_REQUEST = 4;              // Service request
    case COMPLAINT = 5;                    // Complaint
    case SECURITY = 6;                     // Security
    case PERFORMANCE = 7;                  // Performance
    case USABILITY = 8;                    // Usability
    case MAINTENANCE = 9;                  // Maintenance
    case CONFIGURATION = 10;               // Configuration
    case DATA_ISSUE = 11;                  // Data issue
    case INTEGRATION = 12;                 // Integration
    case TRAINING = 13;                    // Training
    case DOCUMENTATION = 14;               // Documentation
    case LEGAL = 15;                       // Legal
    case BILLING = 16;                     // Billing
    case OTHER_REQUEST = 17;               // Other request
    case FEEDBACK = 18;                    // Feedback
    case FEATURE_REQUEST = 19;             // Feature request
    case SYSTEM_OUTAGE = 20;               // System outage
    case ACCOUNT_ISSUE = 21;               // Account issue
    case ACCESS_ISSUE = 22;                // Access issue
    case NETWORK_ISSUE = 23;               // Network issue
    case HARDWARE_ISSUE = 24;              // Hardware issue
    case SOFTWARE_ISSUE = 25;              // Software issue
    case PERFORMANCE_ISSUE = 26;           // Performance issue
    case COMPATIBILITY_ISSUE = 27;         // Compatibility issue
    case SECURITY_BREACH = 28;             // Security breach
    case PRIVACY_ISSUE = 29;               // Privacy issue
    case COMPLIANCE_ISSUE = 30;            // Compliance issue
    case OTHER_INCIDENT = 31;              // Other incident
    case SERVICE_DISRUPTION = 32;          // Service disruption
    case SERVICE_LEVEL_AGREEMENT = 33;     // Service level agreement
    case SERVICE_ENHANCEMENT = 34;         // Service enhancement
    case SERVICE_OPTIMIZATION = 35;        // Service optimization
    case SERVICE_REVIEW = 36;              // Service review
    case SERVICE_FEEDBACK = 37;            // Service feedback
    case SERVICE_COMPLAINT = 38;           // Service complaint
    case SERVICE_REQUEST_FULFILLMENT = 39; // Service request fulfillment
    case SERVICE_REQUEST_CANCELLATION = 40; // Service request cancellation
    case SERVICE_REQUEST_MODIFICATION = 41; // Service request modification
    case SERVICE_REQUEST_STATUS_UPDATE = 42; // Service request status update
    case SERVICE_REQUEST_ESCALATION = 43;  // Service request escalation
    case SERVICE_REQUEST_RESOLUTION = 44;  // Service request resolution

    public function trans(TranslatorInterface $translator, ?string $locale = null): string
    {
        return $translator->trans('incidence_type.'.$this->name, [], 'enums', $locale);
    }
}
