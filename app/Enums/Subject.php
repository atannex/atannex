<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * Subject Enum
 *
 * Represents message or contact form subjects.
 */
final class Subject extends Enum
{
    #[Description('Writing Article')]
    public const WRITING_ARTICLE = 'writing_article';

    #[Description('Become Author')]
    public const BECOME_AUTHOR = 'become_author';

    #[Description('Guest Posting')]
    public const GUEST_POSTING = 'guest_posting';

    #[Description('Personal Question')]
    public const PERSONAL_QUESTION = 'personal_question';

    #[Description('Collaboration Request')]
    public const COLLABORATION_REQUEST = 'collaboration_request';

    #[Description('Feedback')]
    public const FEEDBACK = 'feedback';

    #[Description('Technical Support')]
    public const TECHNICAL_SUPPORT = 'technical_support';

    #[Description('Partnership Inquiry')]
    public const PARTNERSHIP_INQUIRY = 'partnership_inquiry';

    #[Description('Sponsorship Request')]
    public const SPONSORSHIP_REQUEST = 'sponsorship_request';

    #[Description('Content Submission')]
    public const CONTENT_SUBMISSION = 'content_submission';

    #[Description('Media Inquiry')]
    public const MEDIA_INQUIRY = 'media_inquiry';

    #[Description('Event Invitation')]
    public const EVENT_INVITATION = 'event_invitation';

    #[Description('Advertising Inquiry')]
    public const ADVERTISING_INQUIRY = 'advertising_inquiry';

    #[Description('Job Application')]
    public const JOB_APPLICATION = 'job_application';

    #[Description('Internship Application')]
    public const INTERNSHIP_APPLICATION = 'internship_application';

    #[Description('Press Release')]
    public const PRESS_RELEASE = 'press_release';

    #[Description('Product Inquiry')]
    public const PRODUCT_INQUIRY = 'product_inquiry';

    #[Description('Complaint')]
    public const COMPLAINT = 'complaint';

    #[Description('Suggestion')]
    public const SUGGESTION = 'suggestion';

    #[Description('Testimonial')]
    public const TESTIMONIAL = 'testimonial';

    #[Description('Other')]
    public const OTHER = 'other';
}
