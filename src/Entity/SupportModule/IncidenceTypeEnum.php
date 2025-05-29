<?php

namespace App\Entity\SupportModule;

enum IncidenceTypeEnum: string
{
    case BUG = 'bug';
    case CHANGE_REQUEST = 'change request';
    case OTHER = 'other';
    case INCIDENT = 'incident';
    case SERVICE_REQUEST = 'service request';
    case COMPLAINT = 'complaint';
    case SECURITY = 'security';
    case PERFORMANCE = 'performance';
    case USABILITY = 'usability';
    case MAINTENANCE = 'maintenance';
    case CONFIGURATION = 'configuration';
    case DATA_ISSUE = 'data issue';
    case INTEGRATION = 'integration';
    case TRAINING = 'training';
    case DOCUMENTATION = 'documentation';
    case LEGAL = 'legal';
    case BILLING = 'billing';
    case OTHER_REQUEST = 'other request';
    case FEEDBACK = 'feedback';
    case FEATURE_REQUEST = 'feature request';
    case SYSTEM_OUTAGE = 'system outage';
    case ACCOUNT_ISSUE = 'account issue';
    case ACCESS_ISSUE = 'access issue';
    case NETWORK_ISSUE = 'network issue';
    case HARDWARE_ISSUE = 'hardware issue';
    case SOFTWARE_ISSUE = 'software issue';
    case PERFORMANCE_ISSUE = 'performance issue';
    case COMPATIBILITY_ISSUE = 'compatibility issue';
    case SECURITY_BREACH = 'security breach';
    case PRIVACY_ISSUE = 'privacy issue';
    case COMPLIANCE_ISSUE = 'compliance issue';
    case OTHER_INCIDENT = 'other incident';
    case SERVICE_DISRUPTION = 'service disruption';
    case SERVICE_LEVEL_AGREEMENT = 'service level agreement';
    case SERVICE_ENHANCEMENT = 'service enhancement';
    case SERVICE_OPTIMIZATION = 'service optimization';
    case SERVICE_REVIEW = 'service review';
    case SERVICE_FEEDBACK = 'service feedback';
    case SERVICE_COMPLAINT = 'service complaint';
    case SERVICE_REQUEST_FULFILLMENT = 'service request fulfillment';
    case SERVICE_REQUEST_CANCELLATION = 'service request cancellation';
    case SERVICE_REQUEST_MODIFICATION = 'service request modification';
    case SERVICE_REQUEST_STATUS_UPDATE = 'service request status update';
    case SERVICE_REQUEST_ESCALATION = 'service request escalation';
    case SERVICE_REQUEST_RESOLUTION = 'service request resolution'; 
}