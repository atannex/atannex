<?php

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * Enum representing message subjects.
 *
 * @method static static WritingArticle()
 * @method static static BecomeAuthor()
 * @method static static GuestPosting()
 * @method static static PersonalQuestion()
 * @method static static CollaborationRequest()
 * @method static static Feedback()
 * @method static static TechnicalSupport()
 * @method static static PartnershipInquiry()
 * @method static static SponsorshipRequest()
 * @method static static ContentSubmission()
 * @method static static MediaInquiry()
 * @method static static EventInvitation()
 * @method static static AdvertisingInquiry()
 * @method static static JobApplication()
 * @method static static InternshipApplication()
 * @method static static PressRelease()
 * @method static static ProductInquiry()
 * @method static static Complaint()
 * @method static static Suggestion()
 * @method static static Testimonial()
 * @method static static Other()
 */
final class Subject extends Enum
{
    const WritingArticle = 'Writing Article';

    const BecomeAuthor = 'Become Author';

    const GuestPosting = 'Guest Posting';

    const PersonalQuestion = 'Personal Question';

    const CollaborationRequest = 'Collaboration Request';

    const Feedback = 'Feedback';

    const TechnicalSupport = 'Technical Support';

    const PartnershipInquiry = 'Partnership Inquiry';

    const SponsorshipRequest = 'Sponsorship Request';

    const ContentSubmission = 'Content Submission';

    const MediaInquiry = 'Media Inquiry';

    const EventInvitation = 'Event Invitation';

    const AdvertisingInquiry = 'Advertising Inquiry';

    const JobApplication = 'Job Application';

    const InternshipApplication = 'Internship Application';

    const PressRelease = 'Press Release';

    const ProductInquiry = 'Product Inquiry';

    const Complaint = 'Complaint';

    const Suggestion = 'Suggestion';

    const Testimonial = 'Testimonial';

    const Other = 'Other';
}
