<?php

declare(strict_types=1);

namespace App\Enums\Checks;

use BenSampo\Enum\Attributes\Description;
use BenSampo\Enum\Enum;

/**
 * RestrictedNames Enum
 *
 * Represents offensive, adult, or otherwise prohibited words.
 */
final class RestrictedNames extends Enum
{
    #[Description('sex')]
    public const SEX = 'sex';

    #[Description('porn')]
    public const PORN = 'porn';

    #[Description('xxx')]
    public const XXX = 'xxx';

    #[Description('nude')]
    public const NUDE = 'nude';

    #[Description('naked')]
    public const NAKED = 'naked';

    #[Description('adult')]
    public const ADULT = 'adult';

    #[Description('erotic')]
    public const EROTIC = 'erotic';

    #[Description('obscene')]
    public const OBSCENE = 'obscene';

    #[Description('vulgar')]
    public const VULGAR = 'vulgar';

    #[Description('offensive')]
    public const OFFENSIVE = 'offensive';

    #[Description('explicit')]
    public const EXPLICIT = 'explicit';

    #[Description('fetish')]
    public const FETISH = 'fetish';

    #[Description('gore')]
    public const GORE = 'gore';

    #[Description('violent')]
    public const VIOLENT = 'violent';

    #[Description('abuse')]
    public const ABUSE = 'abuse';

    #[Description('rape')]
    public const RAPE = 'rape';

    #[Description('torture')]
    public const TORTURE = 'torture';

    #[Description('murder')]
    public const MURDER = 'murder';

    #[Description('kill')]
    public const KILL = 'kill';

    #[Description('drugs')]
    public const DRUGS = 'drugs';

    #[Description('weed')]
    public const WEED = 'weed';

    #[Description('heroin')]
    public const HEROIN = 'heroin';

    #[Description('cocaine')]
    public const COCAINE = 'cocaine';

    #[Description('meth')]
    public const METH = 'meth';

    #[Description('lsd')]
    public const LSD = 'lsd';

    #[Description('ecstasy')]
    public const ECSTASY = 'ecstasy';

    #[Description('opium')]
    public const OPIUM = 'opium';

    #[Description('sexy')]
    public const SEXY = 'sexy';

    #[Description('hate')]
    public const HATE = 'hate';

    #[Description('racism')]
    public const RACISM = 'racism';

    #[Description('bigot')]
    public const BIGOT = 'bigot';

    #[Description('nigger')]
    public const NIGGER = 'nigger';

    #[Description('faggot')]
    public const FAGGOT = 'faggot';

    #[Description('piss')]
    public const PISS = 'piss';

    #[Description('cunt')]
    public const CUNT = 'cunt';

    #[Description('slut')]
    public const SLUT = 'slut';

    #[Description('whore')]
    public const WHORE = 'whore';

    #[Description('bitch')]
    public const BITCH = 'bitch';

    #[Description('bastard')]
    public const BASTARD = 'bastard';

    #[Description('ass')]
    public const ASS = 'ass';

    #[Description('cock')]
    public const COCK = 'cock';

    #[Description('dick')]
    public const DICK = 'dick';

    #[Description('pussy')]
    public const PUSSY = 'pussy';

    #[Description('blowjob')]
    public const BLOWJOB = 'blowjob';

    #[Description('cum')]
    public const CUM = 'cum';

    #[Description('anal')]
    public const ANAL = 'anal';

    #[Description('gay')]
    public const GAY = 'gay';

    #[Description('lesbian')]
    public const LESBIAN = 'lesbian';

    #[Description('transexual')]
    public const TRANSEXUAL = 'transexual';

    #[Description('tranny')]
    public const TRANNY = 'tranny';

    #[Description('bestiality')]
    public const BESTIALITY = 'bestiality';

    #[Description('zoo')]
    public const ZOO = 'zoo';

    #[Description('furry')]
    public const FURRY = 'furry';

    #[Description('horny')]
    public const HORNY = 'horny';

    #[Description('pedo')]
    public const PEDO = 'pedo';

    #[Description('pedophile')]
    public const PEDOPHILE = 'pedophile';

    #[Description('child')]
    public const CHILD = 'child';

    #[Description('rimming')]
    public const RIMMING = 'rimming';

    #[Description('bdsm')]
    public const BDSM = 'bdsm';

    #[Description('dominate')]
    public const DOMINATE = 'dominate';

    #[Description('submissive')]
    public const SUBMISSIVE = 'submissive';

    #[Description('snuff')]
    public const SNUFF = 'snuff';

    #[Description('spike')]
    public const SPIKE = 'spike';

    #[Description('spank')]
    public const SPANK = 'spank';

    #[Description('fuck')]
    public const FUCK = 'fuck';

    #[Description('strap-on')]
    public const STRAP_ON = 'strap-on';

    #[Description('orgy')]
    public const ORGY = 'orgy';

    #[Description('bondage')]
    public const BONDAGE = 'bondage';

    #[Description('exhibitionism')]
    public const EXHIBITIONISM = 'exhibitionism';

    #[Description('voyeurism')]
    public const VOYEURISM = 'voyeurism';

    #[Description('incest')]
    public const INCEST = 'incest';

    #[Description('necrophilia')]
    public const NECROPHILIA = 'necrophilia';

    #[Description('urophilia')]
    public const UROPHILIA = 'urophilia';

    #[Description('coprophilia')]
    public const COPROPHILIA = 'coprophilia';

    #[Description('hentai')]
    public const HENTAI = 'hentai';

    #[Description('lust')]
    public const LUST = 'lust';

    #[Description('clit')]
    public const CLIT = 'clit';

    #[Description('porno')]
    public const PORNO = 'porno';

    #[Description('violence')]
    public const VIOLENCE = 'violence';

    #[Description('methamphetamine')]
    public const METHAMPHETAMINE = 'methamphetamine';

    #[Description('heroines')]
    public const HEROINES = 'heroines';

    #[Description('killer')]
    public const KILLER = 'killer';

    #[Description('guns')]
    public const GUNS = 'guns';

    #[Description('shot')]
    public const SHOT = 'shot';

    #[Description('suicide')]
    public const SUICIDE = 'suicide';

    #[Description('self-harm')]
    public const SELF_HARM = 'self-harm';

    #[Description('cutting')]
    public const CUTTING = 'cutting';

    #[Description('masochism')]
    public const MASOCHISM = 'masochism';

    #[Description('sadism')]
    public const SADISM = 'sadism';

    #[Description('homicide')]
    public const HOMICIDE = 'homicide';

    #[Description('rogue')]
    public const ROGUE = 'rogue';

    #[Description('assassin')]
    public const ASSASSIN = 'assassin';

    #[Description('terror')]
    public const TERROR = 'terror';

    #[Description('terrorist')]
    public const TERRORIST = 'terrorist';

    #[Description('bomb')]
    public const BOMB = 'bomb';

    #[Description('explosive')]
    public const EXPLOSIVE = 'explosive';

    #[Description('nuke')]
    public const NUKE = 'nuke';

    #[Description('radiation')]
    public const RADIATION = 'radiation';

    #[Description('poison')]
    public const POISON = 'poison';
}
