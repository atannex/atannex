<?php

declare(strict_types=1);

namespace App\Enums\Checks;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

/**
 * SimpleNames Enum
 *
 * Represents common simple, generic, or system-related names.
 */
final class SimpleNames extends Enum
{
    #[Description('abc')]
    public const ABC = 'abc';

    #[Description('test')]
    public const TEST = 'test';

    #[Description('name')]
    public const NAME = 'name';

    #[Description('admin')]
    public const ADMIN = 'admin';

    #[Description('user')]
    public const USER = 'user';

    #[Description('guest')]
    public const GUEST = 'guest';

    #[Description('foo')]
    public const FOO = 'foo';

    #[Description('bar')]
    public const BAR = 'bar';

    #[Description('baz')]
    public const BAZ = 'baz';

    #[Description('root')]
    public const ROOT = 'root';

    #[Description('system')]
    public const SYSTEM = 'system';

    #[Description('superuser')]
    public const SUPERUSER = 'superuser';

    #[Description('moderator')]
    public const MODERATOR = 'moderator';

    #[Description('support')]
    public const SUPPORT = 'support';

    #[Description('client')]
    public const CLIENT = 'client';

    #[Description('customer')]
    public const CUSTOMER = 'customer';

    #[Description('manager')]
    public const MANAGER = 'manager';

    #[Description('staff')]
    public const STAFF = 'staff';

    #[Description('employee')]
    public const EMPLOYEE = 'employee';

    #[Description('director')]
    public const DIRECTOR = 'director';

    #[Description('executive')]
    public const EXECUTIVE = 'executive';

    #[Description('leader')]
    public const LEADER = 'leader';

    #[Description('member')]
    public const MEMBER = 'member';

    #[Description('guest_user')]
    public const GUEST_USER = 'guest_user';

    #[Description('temp_user')]
    public const TEMP_USER = 'temp_user';

    #[Description('account')]
    public const ACCOUNT = 'account';

    #[Description('profile')]
    public const PROFILE = 'profile';

    #[Description('settings')]
    public const SETTINGS = 'settings';

    #[Description('config')]
    public const CONFIG = 'config';

    #[Description('help')]
    public const HELP = 'help';

    #[Description('support_user')]
    public const SUPPORT_USER = 'support_user';

    #[Description('contact')]
    public const CONTACT = 'contact';

    #[Description('owner')]
    public const OWNER = 'owner';

    #[Description('developer')]
    public const DEVELOPER = 'developer';

    #[Description('designer')]
    public const DESIGNER = 'designer';

    #[Description('writer')]
    public const WRITER = 'writer';

    #[Description('editor')]
    public const EDITOR = 'editor';

    #[Description('reviewer')]
    public const REVIEWER = 'reviewer';

    #[Description('subscriber')]
    public const SUBSCRIBER = 'subscriber';

    #[Description('administrator')]
    public const ADMINISTRATOR = 'administrator';

    #[Description('operator')]
    public const OPERATOR = 'operator';

    #[Description('analyst')]
    public const ANALYST = 'analyst';

    #[Description('consultant')]
    public const CONSULTANT = 'consultant';

    #[Description('controller')]
    public const CONTROLLER = 'controller';

    #[Description('supervisor')]
    public const SUPERVISOR = 'supervisor';

    #[Description('coordinator')]
    public const COORDINATOR = 'coordinator';

    #[Description('engineer')]
    public const ENGINEER = 'engineer';

    #[Description('technician')]
    public const TECHNICIAN = 'technician';

    #[Description('instructor')]
    public const INSTRUCTOR = 'instructor';

    #[Description('trainer')]
    public const TRAINER = 'trainer';

    #[Description('planner')]
    public const PLANNER = 'planner';
}
