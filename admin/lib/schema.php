<?php
/**
 * The single source of truth for what is editable and what it defaults to.
 * Templates read values through f()/g(); the admin builds its forms from here.
 * Adding a field here makes it appear in the admin automatically.
 *
 * Types: text | textarea | image | repeater
 * textarea supports blank-line paragraphs, **bold** and [text](url) only.
 */
declare(strict_types=1);

function schema_site(): array {
    return [
        'label'  => 'Site-wide settings',
        'intro'  => 'These appear on every page. The helpline numbers are the most important content on this website — check them on a real phone after saving.',
        'fields' => [
            'org_name'        => ['label' => 'Organisation name', 'type' => 'text', 'default' => 'Strandfold Support Foundation'],
            'tagline'         => ['label' => 'Tagline', 'type' => 'text', 'default' => 'One strand breaks. Folded, we hold.'],
            'helpline'        => ['label' => 'Helpline number (displayed)', 'type' => 'text', 'default' => '[XXX-XXX-XXXX]', 'help' => 'Shown in the top safety bar on every page.'],
            'helpline_tel'    => ['label' => 'Helpline number (dialled)', 'type' => 'text', 'default' => '+000000000000', 'help' => 'International format, no spaces, e.g. +2348012345678. This is what the phone actually dials.'],
            'helpline_hours'  => ['label' => 'Helpline hours', 'type' => 'text', 'default' => '[hours]'],
            'whatsapp'        => ['label' => 'WhatsApp number (displayed)', 'type' => 'text', 'default' => '[XXX-XXX-XXXX]'],
            'whatsapp_number' => ['label' => 'WhatsApp number (link)', 'type' => 'text', 'default' => '000000000000', 'help' => 'Digits only, country code first, no + and no spaces.'],
            'sms'             => ['label' => 'SMS number (displayed)', 'type' => 'text', 'default' => '[XXX-XXX-XXXX]'],
            'sms_tel'         => ['label' => 'SMS number (link)', 'type' => 'text', 'default' => '+000000000000'],
            'phone'           => ['label' => 'Enquiries phone (displayed)', 'type' => 'text', 'default' => '+234 916 093 8380', 'help' => 'For general and donation enquiries. This is not the helpline.'],
            'phone_tel'       => ['label' => 'Enquiries phone (dialled)', 'type' => 'text', 'default' => '+2349160938380', 'help' => 'International format, no spaces.'],
            'email'           => ['label' => 'General email', 'type' => 'text', 'default' => 'contact@strandfoldsupport.org'],
            'email_media'     => ['label' => 'Media enquiries email', 'type' => 'text', 'default' => 'contact@strandfoldsupport.org'],
            'emergency'       => ['label' => 'Emergency services number', 'type' => 'text', 'default' => '[emergency number]', 'help' => 'Used in the "immediate danger" line that appears beside every contact point.'],
            'address'         => ['label' => 'Office address', 'type' => 'textarea', 'default' => '[Address]'],
            'office_hours'    => ['label' => 'Office hours', 'type' => 'text', 'default' => '[Opening hours]'],
            'reg_number'      => ['label' => 'Registration number', 'type' => 'text', 'default' => '[registration number]'],
            'regulator'       => ['label' => 'Regulator', 'type' => 'text', 'default' => '[CAC / relevant regulator]'],
            'site_url'        => ['label' => 'Website address', 'type' => 'text', 'default' => 'https://strandfoldsupport.org', 'help' => 'Used in the sitemap search engines read. Must start with https://'],
            'decoy_url'       => ['label' => 'Quick Exit destination', 'type' => 'text', 'default' => 'https://www.bbc.com/weather', 'help' => 'Where the red Quick Exit button sends someone. Pick a neutral site a visitor could plausibly have open — local news or weather. Must start with https://'],
        ],
    ];
}

function schema_pages(): array {
    return [

        /* ---------------------------------------------------------------- */
        'index' => [
            'label' => 'Homepage',
            'file'  => 'index.html',
            'title' => 'Strandfold Support Foundation',
            'desc'  => 'Strandfold Support Foundation is a Lagos-based NGO providing trauma-informed counselling, safety planning, referrals and economic rebuilding for women and children affected by domestic and sexual violence. Free. Confidential. Survivor-centred.',
            'fields' => [
                'hero_eyebrow'  => ['label' => 'Hero eyebrow', 'type' => 'text', 'default' => 'Held, not handled'],
                'hero_headline' => ['label' => 'Hero headline', 'type' => 'text', 'default' => 'You reached out. That was the hardest part.'],
                'hero_lede'     => ['label' => 'Hero introduction', 'type' => 'textarea', 'default' => 'Strandfold Support Foundation walks with women overcoming abuse — from the first safe conversation to the life you rebuild after it. Free. Confidential. On your terms.'],
                'hero_cta1'     => ['label' => 'Primary button', 'type' => 'text', 'default' => 'I need help today'],
                'hero_cta2'     => ['label' => 'Secondary button', 'type' => 'text', 'default' => 'Support this work'],
                'hero_image'    => ['label' => 'Hero image', 'type' => 'image', 'ratio' => '4 / 5', 'help' => 'Optional. Hands, textiles, doorways, light through a window. Never a survivor\'s face.'],

                'stats_eyebrow' => ['label' => 'Stats band label', 'type' => 'text', 'default' => 'The reality'],
                'stats_source'  => ['label' => 'Source note', 'type' => 'textarea', 'default' => 'Source: [cite]. Figures reviewed [month, year]. We publish only figures we can cite. Where a number is bracketed, it has not yet been verified for release.'],

                'do_eyebrow'    => ['label' => 'Section label', 'type' => 'text', 'default' => 'What we actually do'],
                'do_headline'   => ['label' => 'Section headline', 'type' => 'text', 'default' => 'Getting out is only the beginning.'],
                'do_body'       => ['label' => 'Section body', 'type' => 'textarea', 'default' => "Leaving an abusive relationship does not mean the struggle is over.\n\nFor many women, it is the beginning of a much longer journey — healing from what they have experienced, rebuilding their confidence, caring for their children, finding stability and learning how to move forward again.\n\nAt Strandfold Support Foundation, we believe that healing requires more than simply leaving. It requires a safe space to talk, professional psychological support and someone to walk alongside you as you begin to rebuild.\n\nOur work begins with counselling and emotional support. Where we are able and where resources permit, we also provide practical assistance to help address some of the challenges that may stand in the way of rebuilding.\n\nWe may not always be able to provide everything a woman needs. But we believe that every woman deserves to be heard, supported and treated with dignity as she finds her way forward.\n\n**Getting out is not the end of the story. It is the beginning of rebuilding a life.**"],

                'quote_text'    => ['label' => 'Survivor quote', 'type' => 'textarea', 'default' => 'The work you are doing is good. It is a good thing you are doing.'],
                'quote_cite'    => ['label' => 'Quote attribution', 'type' => 'text', 'default' => 'Ada, Strandfold participant. Name changed. Shared with permission.', 'help' => 'Never a real name alongside a photograph. Initials or "name withheld" only.'],

                'band_eyebrow'  => ['label' => 'Red band label', 'type' => 'text', 'default' => 'Why we’re called Strandfold'],
                'band_headline' => ['label' => 'Red band headline', 'type' => 'text', 'default' => 'One strand breaks. Folded, we hold.'],
                'band_body'     => ['label' => 'Red band body', 'type' => 'textarea', 'default' => "A single strand is fragile. Pull it and it breaks. But strands folded together — twisted, layered, held — become rope strong enough to carry weight that would snap any one of them alone.\n\nThe older sense of **fold** is a shelter: an enclosure where the vulnerable are kept through the night. Not forever. Long enough for morning.\n\nThat is the work. Not rescue. Not charity. A place to be held, and enough hands folded together to carry what one woman was never meant to carry by herself."],
                'band_image'    => ['label' => 'Red band image', 'type' => 'image', 'ratio' => '4 / 3', 'help' => 'Sits beside the red panel. Abstract or environmental — no identifiable faces.'],

                'close_headline' => ['label' => 'Closing headline', 'type' => 'text', 'default' => 'If you are reading this for someone else, you are already helping.'],
                'close_body'     => ['label' => 'Closing body', 'type' => 'textarea', 'default' => 'Send her this page. Or call us yourself and ask what to do — you do not need her permission to ask us how to support her well. We will not contact her without her consent.'],
            ],
            'repeaters' => [
                'stats' => [
                    'label'  => 'Statistics',
                    'help'   => 'Publish only figures you can cite. If a number cannot be sourced, delete the row rather than estimating.',
                    'fields' => [
                        'number' => ['label' => 'Figure', 'type' => 'text'],
                        'label'  => ['label' => 'Description', 'type' => 'textarea'],
                    ],
                    'default' => [
                        ['number' => '[XX]%', 'label' => 'of women in Lagos State have experienced physical or sexual violence from a partner.'],
                        ['number' => '[XX]%', 'label' => 'of them never reported it.'],
                        ['number' => '[XX]',  'label' => 'women walked through our doors last year.'],
                        ['number' => '[XX]',  'label' => 'of them are now living independently.'],
                    ],
                ],
                'programmes' => [
                    'label'  => 'Programme cards',
                    'fields' => [
                        'icon'  => ['label' => 'Icon', 'type' => 'icon'],
                        'title' => ['label' => 'Title', 'type' => 'text'],
                        'body'  => ['label' => 'Description', 'type' => 'textarea'],
                        'image' => ['label' => 'Image', 'type' => 'image', 'ratio' => '1 / 1'],
                    ],
                    'default' => [
                        ['icon' => 'mind',   'title' => 'Inner Work',      'body' => 'Trauma-informed counselling, one-on-one and in survivor circles, at no cost.'],
                        ['icon' => 'sprout', 'title' => 'Rebuild',         'body' => 'Skills training, financial literacy, job placement and small business start-up grants.'],
                    ],
                ],
                'ways' => [
                    'label'  => 'Three ways to stand with us',
                    'fields' => [
                        'title' => ['label' => 'Title', 'type' => 'text'],
                        'body'  => ['label' => 'Description', 'type' => 'textarea'],
                        'link'  => ['label' => 'Link URL', 'type' => 'text'],
                        'cta'   => ['label' => 'Link text', 'type' => 'text'],
                    ],
                    'default' => [
                        ['title' => 'Give',      'body' => 'Fund a woman’s counselling, medical care or the start-up grant for her business.', 'link' => '/get-involved#give', 'cta' => 'Donate'],
                        ['title' => 'Volunteer', 'body' => 'Counsellors, trainers, drivers, admin support.',        'link' => '/get-involved#volunteer', 'cta' => 'Apply'],
                        ['title' => 'Partner',   'body' => 'Corporate, faith-based and institutional partnerships.',         'link' => '/get-involved#partner', 'cta' => 'Talk to us'],
                    ],
                ],
            ],
        ],

        /* ---------------------------------------------------------------- */
        'get-help' => [
            'label' => 'Get Help',
            'file'  => 'get-help.html',
            'title' => 'Resources — Strandfold',
            'desc'  => 'Confidential, free support for women experiencing or recovering from abuse. Talk to someone today.',
            'note'  => 'The browser tab title is deliberately vague ("Resources — Strandfold"). Do not change it to anything naming abuse.',
            'fields' => [
                'hero_eyebrow'  => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Get help'],
                'hero_headline' => ['label' => 'Headline', 'type' => 'text', 'default' => 'You don’t need proof. You don’t need a plan. You just need to start.'],
                'hero_lede'     => ['label' => 'Introduction', 'type' => 'textarea', 'default' => 'You do not have to call it abuse. You do not have to be sure. You do not have to have decided anything. If something in your relationship, your home or your workplace is making you afraid, you can talk to us.'],
                'hero_assure'   => ['label' => 'Reassurance line', 'type' => 'textarea', 'default' => '**Everything you tell us is confidential. We will not contact anyone without your permission. There is no cost.**'],
                'steps_eyebrow' => ['label' => 'Steps label', 'type' => 'text', 'default' => 'What happens when you contact us'],
                'steps_headline'=> ['label' => 'Steps headline', 'type' => 'text', 'default' => 'Four steps. No forms first.'],
                'close_headline'=> ['label' => 'Closing headline', 'type' => 'text', 'default' => 'You are not the first. You will not be alone.'],
                'close_body'    => ['label' => 'Closing body', 'type' => 'textarea', 'default' => 'There is no minimum threshold. If you are asking whether it is bad enough to count, you are welcome here.'],
            ],
            'repeaters' => [
                'steps' => [
                    'label'  => 'What happens when you contact us',
                    'fields' => ['title' => ['label' => 'Title', 'type' => 'text'], 'body' => ['label' => 'Description', 'type' => 'textarea']],
                    'default' => [
                        ['title' => 'You talk. We listen.',      'body' => 'Somewhere private, by phone or in person. No interrogation. You share what you want to share, and we ask your permission before we write anything down.'],
                        ['title' => 'We check you are safe now.', 'body' => 'If you are in danger, we help you make a safety plan and connect you to emergency services. If you or a child needs a doctor, that comes first.'],
                        ['title' => 'You choose what happens next.', 'body' => 'Counselling with us, and referrals only where you want them: medical care, legal aid, a shelter, the police, or help to earn an income. We do not decide anything for you.'],
                        ['title' => 'We stay.',                  'body' => 'Follow-up sessions and regular check-ins for as long as you want them. You can close your case whenever you choose, and if you come back later, the door is open.'],
                    ],
                ],
                'accordions' => [
                    'label'  => 'Expandable sections',
                    'help'   => 'The anchor is what footer links point at — changing it breaks those links.',
                    'fields' => [
                        'anchor' => ['label' => 'Anchor id', 'type' => 'text'],
                        'title'  => ['label' => 'Heading', 'type' => 'text'],
                        'body'   => ['label' => 'Content', 'type' => 'textarea'],
                    ],
                    'default' => [
                        ['anchor' => 'is-this-abuse', 'title' => 'Is this abuse?', 'body' => "Abuse is not only physical. If any of these are familiar, you are welcome here:\n\n**Physical** — hitting, choking, restraining, throwing objects, harm to your children or pets.\n\n**Emotional and psychological** — constant criticism, humiliation, threats, silent treatment as punishment, being told you are mad or imagining things.\n\n**Sexual** — any sexual act you did not freely agree to, including within marriage.\n\n**Financial** — controlling your money, preventing you from working, taking your earnings, keeping you in debt.\n\n**Digital** — monitoring your phone, tracking your location, demanding passwords, sharing your images without consent.\n\n**Isolation** — being cut off from family, friends, church, work or medical care.\n\n**Spiritual** — using scripture, faith or a religious leader’s authority to justify control or to force you to stay.\n\n**You do not need to tick every box. One is enough.**"],
                        ['anchor' => 'safety-planning', 'title' => 'Safety planning', 'body' => "A safety plan is not a decision to leave. It is a set of options ready in case you need them.\n\nIdentify one person outside the home who knows your situation.\n\nKnow which room in the house has an exit and no weapons; avoid kitchens and bathrooms during conflict.\n\nKeep a small bag of essentials somewhere safe — with a neighbour, at work, at church.\n\nPhotograph and store key documents in a private cloud account he does not know about: ID, birth certificates, marriage certificate, property papers, medical records.\n\nAgree a code word with someone you trust that means “call for help”.\n\nMemorise two phone numbers in case your phone is taken.\n\nIf you have children, agree on where they go and who they call.\n\nOur team will work through a full, personalised plan with you. This list is a starting point only."],
                        ['anchor' => 'friends-family', 'title' => 'For friends and family', 'body' => "**Say:** “I believe you.” · “This is not your fault.” · “I’m here whether you stay or go.”\n\n**Don’t say:** “Why don’t you just leave?” · “Are you sure it was that bad?” · “Think about the children.”\n\nDo not confront the abuser. Do not issue ultimatums. Do not withdraw when she does not do what you want. Isolation is the abuser’s most effective tool — your steady presence is the counterweight.\n\n**Call us.** You can get guidance on supporting her without breaching her trust."],
                        ['anchor' => 'shelter', 'title' => 'Finding a shelter', 'body' => "We do not publish shelter addresses. Publishing them puts every woman inside at risk.\n\nWhat we do instead: we refer you to a partner facility we have inspected ourselves, we confirm there is space before you travel, and we arrange transport where we can.\n\nTo start that process, call or message the helpline. You do not need a referral from anyone."],
                        ['anchor' => 'cost', 'title' => 'What this costs you', 'body' => "Nothing. Counselling, safety planning, referrals and programme access are free. Donors cover the cost.\n\nNobody at Strandfold will ever ask you for money, gifts or favours, and nobody will ask you to tell your story publicly in exchange for help. If anyone claiming to represent us does, that is not us — please [tell us](/policies#complaints)."],
                    ],
                ],
            ],
        ],

        /* ---------------------------------------------------------------- */
        'programmes' => [
            'label' => 'Programmes',
            'file'  => 'programmes.html',
            'title' => 'Programmes — Strandfold Support Foundation',
            'desc'  => 'Strandfold\'s programmes — trauma counselling, economic rebuilding and community prevention for women overcoming abuse.',
            'fields' => [
                'hero_eyebrow'  => ['label' => 'Eyebrow', 'type' => 'text', 'default' => ''],
                'hero_headline' => ['label' => 'Headline', 'type' => 'text', 'default' => 'Programmes'],
                'hero_lede'     => ['label' => 'Introduction', 'type' => 'textarea', 'default' => ''],
                'close_headline'=> ['label' => 'Closing headline', 'type' => 'text', 'default' => 'Safety without a livelihood is a temporary safety.'],
                'close_body'    => ['label' => 'Closing body', 'type' => 'textarea', 'default' => 'Most interventions stop at rescue. Funding Strandfold funds the years after it.'],
            ],
            'repeaters' => [
                'programmes' => [
                    'label'  => 'Programmes',
                    'fields' => [
                        'anchor'   => ['label' => 'Anchor id', 'type' => 'text'],
                        'icon'     => ['label' => 'Icon', 'type' => 'icon'],
                        'title'    => ['label' => 'Name', 'type' => 'text'],
                        'for'      => ['label' => 'Strapline', 'type' => 'text'],
                        'image'    => ['label' => 'Image', 'type' => 'image', 'ratio' => '3 / 2'],
                        'includes' => ['label' => 'What it includes', 'type' => 'textarea', 'help' => 'One item per line.'],
                        'note_h'   => ['label' => 'Side box heading', 'type' => 'text'],
                        'note_b'   => ['label' => 'Side box text', 'type' => 'textarea'],
                    ],
                    'default' => [
                        ['anchor' => 'inner-work', 'icon' => 'mind', 'title' => 'Inner Work', 'for' => 'Trauma counselling · For any woman or child who has experienced abuse, at any stage — including years afterwards', 'includes' => "One-on-one counselling with trained, trauma-informed practitioners\nSafety planning and risk assessment\nWeekly survivor support circles\nGrief, anxiety and PTSD support\nChild and adolescent counselling for children who witnessed abuse\nReferral to psychiatric care where clinically indicated", 'note_h' => 'Cost', 'note_b' => "**Free.** Funded entirely by donors.\n\nOur counsellors are [credentials]. Sessions are held at [location] and by phone. Nothing discussed is shared outside your clinical file, which is stored securely."],
                        ['anchor' => 'rebuild', 'icon' => 'sprout', 'title' => 'Rebuild', 'for' => 'Economic independence · For women ready to build an income of their own', 'includes' => "Vocational skills training in [tailoring, catering, hairdressing, digital skills, …]\nDigital and financial literacy\nCV writing, interview preparation and job placement support\nSeed grants and starter equipment for small businesses\nTwelve months of business mentorship after start-up\nSavings group membership", 'note_h' => 'Why this exists', 'note_b' => "Financial dependence is the single most common reason women return to abusive partners. Counselling without income is incomplete care.\n\n**Outcome tracked:** percentage of participants earning independent income at 6 and 12 months."],
                        ['anchor' => 'speak-early', 'icon' => 'megaphone', 'title' => 'Speak Early', 'for' => 'Prevention & community · For schools, churches, mosques, workplaces and community groups', 'includes' => "Consent, healthy relationships and warning-signs education for young people\nTraining for pastors, imams and community leaders on responding to disclosure\nWorkplace policy consultation\nFirst-responder training for police, nurses and teachers\nCommunity awareness campaigns", 'note_h' => 'Book a session', 'note_b' => 'Tell us who your group is and roughly how many people will attend, and we will propose a format. [Request a session](/contact)'],
                    ],
                ],
            ],
        ],

        /* ---------------------------------------------------------------- */
        'about' => [
            'label' => 'About',
            'file'  => 'about.html',
            'title' => 'About — Strandfold Support Foundation',
            'desc'  => 'Learn about Strandfold Support Foundation — our story, our approach to trauma-informed care, our team and our commitment to survivor-led support for women overcoming abuse.',
            'fields' => [
                'hero_eyebrow'  => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'About us'],
                'hero_headline' => ['label' => 'Headline', 'type' => 'text', 'default' => 'We stay through the years that follow.'],
                'hero_lede'     => ['label' => 'Introduction', 'type' => 'textarea', 'default' => 'Strandfold is a Lagos-based support organisation for women and children affected by domestic and sexual violence, working at the point where crisis response ends and long-term recovery begins.'],
                'story_headline'=> ['label' => 'Story headline', 'type' => 'text', 'default' => 'Nobody was answering the rest.'],
                'story_body'    => ['label' => 'Our story', 'type' => 'textarea', 'default' => "Strandfold Support Foundation began with **[founding moment — a case, a realisation, a gap someone witnessed]**. In **[year]**, **[founder / founding group]** started with **[modest starting point — a phone line, a room, a WhatsApp group]**.\n\nWhat became clear early was this: the women who reached out were rarely asking to be rescued. They were asking practical questions."],
                'story_quote'   => ['label' => 'Pull quote', 'type' => 'textarea', 'default' => 'Where can I sleep tonight with three children? Can he take the house? If I report it, what happens to my job? Who is going to pay for a lawyer?'],
                'story_body2'   => ['label' => 'Our story (continued)', 'type' => 'textarea', 'default' => "Emergency response answered the first question. Nobody was answering the rest.\n\n**Strandfold was built to answer the rest.**\n\nToday we serve **[number]** women a year across **Lagos State**, with **[number]** staff and **[number]** trained volunteers."],
                'story_image'   => ['label' => 'Story image', 'type' => 'image', 'ratio' => '3 / 2'],
                'band_headline' => ['label' => 'Name band headline', 'type' => 'text', 'default' => 'Why we’re called Strandfold'],
                'band_body'     => ['label' => 'Name band body', 'type' => 'textarea', 'default' => "A single strand is fragile. Pull it and it breaks. But strands folded together — twisted, layered, held — become rope strong enough to carry weight that would snap any one of them alone.\n\nThe older sense of **fold** is a shelter: an enclosure where the vulnerable are kept through the night, not forever, but long enough for morning.\n\nThat is the work. Not rescue. Not charity. A place to be held, and enough hands folded together to carry what one woman was never meant to carry by herself."],
                'band_image'    => ['label' => 'Name band image', 'type' => 'image', 'ratio' => '4 / 3'],
                'approach_headline' => ['label' => 'Approach headline', 'type' => 'text', 'default' => 'Built around how trauma actually behaves.'],
                'values_headline'   => ['label' => 'Values headline', 'type' => 'text', 'default' => 'Five things we hold to.'],
                'dont_headline' => ['label' => 'What we don’t do — headline', 'type' => 'text', 'default' => 'Honesty builds more trust than a long list of claims.'],
                'dont_list'     => ['label' => 'What we don’t do', 'type' => 'textarea', 'help' => 'One item per line.', 'default' => "We are **not an emergency service**. In immediate danger, call [emergency number].\nWe do not operate our own shelter [or: our shelter has limited capacity]. We refer to vetted partner facilities.\nWe are **not a law firm** and do not give legal advice. Where you want it, we refer you to legal aid.\nWe do not confront, contact or negotiate with abusers."],
                'team_headline' => ['label' => 'Team headline', 'type' => 'text', 'default' => 'Who you are trusting.'],
                'team_lede'     => ['label' => 'Team introduction', 'type' => 'textarea', 'default' => 'Survivors need to know who is holding their mental health. Every clinical role below lists a professional credential. Our Board of Trustees is listed separately under governance.'],
                'gov_headline'  => ['label' => 'Governance headline', 'type' => 'text', 'default' => 'Open books, on purpose.'],
                'gov_body'      => ['label' => 'Governance intro', 'type' => 'textarea', 'default' => 'We publish our accounts because women deciding whether to trust us, and donors deciding whether to fund us, both deserve to see the same numbers.'],
                'gov_list'      => ['label' => 'Governance details', 'type' => 'textarea', 'help' => 'One item per line.', 'default' => "Registered with **[CAC / relevant regulator]**, registration number **[XXXX]**.\nGoverned by a Board of Trustees of **[X]** members.\nAnnual reports and audited financials published here: [link].\nSafeguarding and child protection policy: [read the summary](/policies#safeguarding).\nConfidentiality and data protection policy: [read the summary](/policies#confidentiality).\nSexual harassment policy and code of conduct: [read them](/policies#harassment).\nComplaints and whistleblowing: [how to raise a concern](/policies#complaints)."],
            ],
            'repeaters' => [
                'approach' => [
                    'label'  => 'Our approach',
                    'fields' => ['title' => ['label' => 'Title', 'type' => 'text'], 'body' => ['label' => 'Body', 'type' => 'textarea']],
                    'default' => [
                        ['title' => 'Trauma-informed, always', 'body' => "Our team is trained to recognise that trauma changes how a person remembers, speaks, trusts and decides. A survivor who tells her story out of order is not lying. A survivor who returns to her abuser has not failed.\n\nWe build our processes around how trauma actually behaves, not around how we wish it behaved."],
                        ['title' => 'Survivor-led, not survivor-managed', 'body' => 'Women who have come through our programmes sit on our advisory board and shape our services. The people closest to the problem are closest to the solution.'],
                        ['title' => 'We do not pressure anyone to leave', 'body' => "This is the hardest thing to explain to donors and the most important thing we do. On average, a woman attempts to leave an abusive relationship several times before it holds.\n\nEach attempt is information, not failure. A woman who knows we will still be here when she returns is a woman who eventually comes back for the last time."],
                        ['title' => 'Confidentiality you can rely on', 'body' => "We do not share names, case files, photographs or identifying details with anyone — media, donors, family, employers — without explicit written consent, which can be withdrawn at any moment.\n\nThe only exceptions are where the law requires it or someone is at serious risk of harm, and we explain that at the start rather than after the fact."],
                    ],
                ],
                'values' => [
                    'label'  => 'Core values',
                    'fields' => ['icon' => ['label' => 'Icon', 'type' => 'icon'], 'title' => ['label' => 'Title', 'type' => 'text'], 'body' => ['label' => 'Body', 'type' => 'textarea']],
                    'default' => [
                        ['icon' => 'person', 'title' => 'She Leads', 'body' => 'The survivor decides. We do not decide for her, pressure her to leave, or withdraw support if she returns. We provide options, information and honesty, and we respect the choice she makes with them.'],
                        ['icon' => 'lock',   'title' => 'Confidentiality Is Sacred', 'body' => 'What is shared with us stays with us. No names, no photographs, no case details without explicit, written, revocable consent.'],
                        ['icon' => 'heart',  'title' => 'Dignity Over Pity', 'body' => 'We do not trade in tragedy for donations. No shock imagery. No woman is asked to perform her worst day to be worth helping.'],
                        ['icon' => 'check',  'title' => 'Practical Over Performative', 'body' => 'Awareness campaigns do not pay rent. Every programme must produce a measurable change in a woman’s safety, health, legal standing or income.'],
                        ['icon' => 'clock',  'title' => 'The Long Walk', 'body' => 'Recovery is not an event. We measure success in years, not rescues.'],
                    ],
                ],
                'team' => [
                    'label'  => 'Team members',
                    'help'   => 'Staff photographs only, and only with that person’s consent. Never a woman you have supported.',
                    'fields' => [
                        'name'  => ['label' => 'Name', 'type' => 'text'],
                        'role'  => ['label' => 'Role', 'type' => 'text'],
                        'bio'   => ['label' => 'Bio', 'type' => 'textarea'],
                        'image' => ['label' => 'Photograph', 'type' => 'image', 'ratio' => '1 / 1'],
                    ],
                    'default' => [
                        ['name' => '[Full name]', 'role' => '[Role]', 'bio' => '[One-line bio. For clinical staff, include registration body and licence number.]'],
                        ['name' => '[Full name]', 'role' => '[Role]', 'bio' => '[One-line bio.]'],
                        ['name' => '[Full name]', 'role' => '[Role]', 'bio' => '[One-line bio.]'],
                    ],
                ],
            ],
        ],

        /* ---------------------------------------------------------------- */
        'stories' => [
            'label' => 'Stories',
            'file'  => 'stories.html',
            'title' => 'Stories — Strandfold Support Foundation',
            'desc'  => 'Stories of women rebuilding after abuse — shared with full consent, names and details changed to protect identity.',
            'note'  => 'Nothing goes on this page without a signed, revocable consent form on file. Every story carries a consent record; a story cannot be published without one.',
            'fields' => [
                'hero_eyebrow'  => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Stories'],
                'hero_headline' => ['label' => 'Headline', 'type' => 'text', 'default' => 'Stories of hope.'],
                'hero_lede'     => ['label' => 'Introduction', 'type' => 'textarea', 'default' => 'These are not case studies. They are accounts of what women built, in their own words, on their own terms.'],
                'consent_note'  => ['label' => 'Consent notice', 'type' => 'textarea', 'default' => "**Every story on this page is shared with the written, informed consent of the woman it belongs to.** Names, locations and identifying details have been changed.\n\nConsent can be withdrawn at any time, and any story will be removed within 24 hours of that request.\n\nNo photograph on this page depicts a Strandfold participant unless she has specifically asked to be photographed."],
                'rules_headline'=> ['label' => 'Editorial headline', 'type' => 'text', 'default' => 'Dignity over pity.'],
                'rules_body'    => ['label' => 'Editorial body', 'type' => 'textarea', 'default' => 'We start with where she is now, not with what was done to her. We do not trade in tragedy for donations, and no woman is asked to perform her worst day to be worth helping.'],
                'rules_list'    => ['label' => 'Never included', 'type' => 'textarea', 'help' => 'One item per line.', 'default' => "Photographs identifying her\nHer town, if it is small enough to identify her\nHer employer\nHer children’s names or schools\nThe abuser’s name\nGraphic description of violence"],
            ],
            'repeaters' => [
                'stories' => [
                    'label'  => 'Stories',
                    'consent'=> true,
                    'fields' => [
                        'headline' => ['label' => 'Headline (her words, or the turning point)', 'type' => 'text'],
                        'tags'     => ['label' => 'Programme tags', 'type' => 'text', 'help' => 'Comma separated.'],
                        'body'     => ['label' => 'Story', 'type' => 'textarea', 'help' => 'Present first, then briefly what she faced, what she used, what she is building.'],
                        'quote'    => ['label' => 'Her own words', 'type' => 'textarea'],
                        'image'    => ['label' => 'Image', 'type' => 'image', 'ratio' => '3 / 2', 'help' => 'Never her face unless she specifically asked to be photographed.'],
                    ],
                    'default' => [
                        ['headline' => "Abosede … Finding Her Feet Again", 'tags' => 'Inner Work, Rebuild', 'body' => "Abosede came to Strandfold Support Foundation after experiencing domestic abuse and being left to care for her children without the support she needed.\n\nAt Strandfold, she received counselling and emotional support as she began to process what she had experienced and regain her footing.\n\nWe also supported her with accommodation, healthcare and financial assistance to start a small business, giving her an opportunity to begin rebuilding her livelihood.\n\n**Her message to us was simple:**\n\n**“Thanks ma. I really appreciate. You help me greatly. God will continue to bless you ma.”**\n\nHer story is not only about what happened to her. It is about what became possible when she received support to begin again.", 'quote' => '', 'consent' => 'Supplied by Strandfold for publication, October 2026. Replace with the signed consent form reference.'],
                        ['headline' => "Ada … An Opportunity to Start Again", 'tags' => 'Inner Work, Rebuild', 'body' => "Ada came to Strandfold Support Foundation after experiencing domestic violence.\n\nAlongside psychological counselling we provided financial assistance to help her start a small business.\n\nWith that support, Ada began putting her business together and taking steps towards becoming financially independent.\n\n**She expressed her gratitude in her own words:**\n\n**“My appreciation also goes to the organisation, Strandfold Support Foundation. May God Almighty be with you, and may the organisation move higher in Jesus’ name. I really appreciate.”**\n\nShe also told us:\n\n**“The work you are doing is good. It is a good thing you are doing.”**", 'quote' => '', 'consent' => 'Supplied by Strandfold for publication, October 2026. Replace with the signed consent form reference.'],
                        ['headline' => "A Mother and Her Children … Finding Safety and Care", 'tags' => 'Inner Work', 'body' => "She came to us after experiencing abuse and being abandoned by her spouse, leaving her to care for her children in extremely difficult circumstances.\n\nAt a time when the family needed both emotional and practical support, Strandfold provided emotional support, together with medical care and accommodation.\n\nThe intervention gave the family a safer place to stay while addressing their immediate healthcare needs.", 'quote' => '', 'consent' => 'Supplied by Strandfold for publication, October 2026. Replace with the signed consent form reference.'],
                    ],
                ],
            ],
        ],

        /* ---------------------------------------------------------------- */
        'get-involved' => [
            'label' => 'Get Involved',
            'file'  => 'get-involved.html',
            'title' => 'Get Involved — Strandfold Support Foundation',
            'desc'  => 'Donate, volunteer or partner with Strandfold Support Foundation to help women overcoming abuse rebuild independent lives.',
            'fields' => [
                'hero_eyebrow'  => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Get involved'],
                'hero_headline' => ['label' => 'Headline', 'type' => 'text', 'default' => 'Money does specific things here.'],
                'hero_lede'     => ['label' => 'Introduction', 'type' => 'textarea', 'default' => 'Give, volunteer, partner or fundraise. Whichever you choose, it lands as counselling hours, medical care, rent or a start-up grant — not as a campaign about awareness.'],
                'give_headline' => ['label' => 'Give headline', 'type' => 'text', 'default' => 'What your gift buys.'],
                'give_lede'     => ['label' => 'Give introduction', 'type' => 'textarea', 'default' => 'Replace nothing here with guesswork — every figure below is a real unit cost from our own budget.'],
                'bank_account_name'   => ['label' => 'Bank account name', 'type' => 'text', 'default' => 'Strandfold Support Foundation'],
                'bank_name'           => ['label' => 'Bank', 'type' => 'text', 'default' => 'Providus Bank'],
                'bank_account_number' => ['label' => 'Account number', 'type' => 'text', 'default' => '1305210651', 'help' => 'Check this digit by digit after saving. A wrong number sends gifts to a stranger.'],
                'inkind_note'         => ['label' => 'In-kind donations note', 'type' => 'textarea', 'default' => 'To give food, clothing, equipment or other items, or to ask about a donation, call or email us.'],
                'give_once_url' => ['label' => 'Give Once link', 'type' => 'text', 'default' => '', 'help' => 'Your online donation processor URL. Leave blank to hide the button.'],
                'give_monthly_url' => ['label' => 'Give Monthly link', 'type' => 'text', 'default' => '', 'help' => 'Leave blank to hide the button.'],
                'transparency'  => ['label' => 'Transparency note', 'type' => 'textarea', 'default' => "[XX] kobo of every naira goes directly to programmes. Our audited accounts are published annually — [see governance](/about#governance).\n\n**We do not accept donations conditional on access to survivors’ identities or stories.**"],
                'vol_headline'  => ['label' => 'Volunteer headline', 'type' => 'text', 'default' => 'We need specific skills, and we screen everyone.'],
                'vol_body'      => ['label' => 'Volunteer body', 'type' => 'textarea', 'default' => "Every volunteer completes background screening, safeguarding orientation and a confidentiality agreement, and agrees to our [code of conduct](/policies#conduct).\n\n**No exceptions — including for board members and donors.**"],
                'vol_list'      => ['label' => 'Roles needed', 'type' => 'textarea', 'help' => 'One per line.', 'default' => "Licensed counsellors and psychologists\nVocational trainers\nDrivers with valid licences\nAdministrative and data support\nFundraising and communications"],
                'vol_note'      => ['label' => 'Volunteer footnote', 'type' => 'textarea', 'default' => 'Are you a survivor yourself? Yes, you can volunteer — many of our team are. We ask that you be at a stable point in your own recovery, and we will discuss this openly and supportively during screening.'],
                'partner_headline' => ['label' => 'Partner headline', 'type' => 'text', 'default' => 'Institutions can move what individuals cannot.'],
                'partner_body'  => ['label' => 'Partner body', 'type' => 'textarea', 'default' => "Our work is designed to complement Lagos State’s domestic violence response, not duplicate it. We bring grassroots survivor engagement, psychosocial recovery and community-level prevention, backed by documented [safeguarding and data protection policies](/policies).\n\nWe work with government agencies, hospitals, legal aid providers, shelters, faith communities, corporate partners and fellow NGOs. Every referral we make is documented and followed up. Ask us for our capability statement."],
                'partner_list'  => ['label' => 'Partnership options', 'type' => 'textarea', 'help' => 'One per line.', 'default' => "Programme sponsorship\nEmployee giving\nPro bono professional services\nReferral partnerships for medical, legal aid and shelter services\nVenue and equipment provision\nJob placement pipelines for Rebuild graduates"],
                'fund_headline' => ['label' => 'Fundraise headline', 'type' => 'text', 'default' => 'Run one for us.'],
                'fund_body'     => ['label' => 'Fundraise body', 'type' => 'textarea', 'default' => "A birthday fundraiser, a workplace challenge, a church offering, a market day. We will provide materials and a dedicated donation link.\n\nWe will never ask you to use a survivor’s photograph or story to raise money."],
                'fund_image'    => ['label' => 'Fundraise image', 'type' => 'image', 'ratio' => '4 / 3'],
                'careers_headline' => ['label' => 'Careers headline', 'type' => 'text', 'default' => 'Working here.'],
                'careers_body'  => ['label' => 'Careers body', 'type' => 'textarea', 'default' => "Open roles are listed here when we have them. All posts are subject to the same screening, safeguarding training and confidentiality agreement as volunteer roles.\n\n[No open roles at present — or list current vacancies with closing dates and how to apply.]"],
            ],
            'repeaters' => [
                'amounts' => [
                    'label'  => 'Donation table',
                    'help'   => 'Specificity converts. Use real unit costs from your budget, never round guesses.',
                    'fields' => ['amount' => ['label' => 'Amount', 'type' => 'text'], 'does' => ['label' => 'What it does', 'type' => 'text']],
                    'default' => [
                        ['amount' => '[₦X]', 'does' => 'One counselling session for one woman'],
                        ['amount' => '[₦X]', 'does' => 'A week of emergency accommodation and meals'],
                        ['amount' => '[₦X]', 'does' => 'Medical care for a woman or child who needs it'],
                        ['amount' => '[₦X]', 'does' => 'A full vocational training course'],
                        ['amount' => '[₦X]', 'does' => 'A start-up grant and equipment for a small business'],
                        ['amount' => '[₦X] / month', 'does' => 'Sponsors one woman through a full year of care'],
                    ],
                ],
            ],
        ],

        /* ---------------------------------------------------------------- */
        'faq' => [
            'label' => 'FAQ',
            'file'  => 'faq.html',
            'title' => 'Questions — Strandfold',
            'desc'  => 'Answers to the questions women most often ask before contacting us: cost, confidentiality, reporting to police, and what happens if you go back.',
            'fields' => [
                'hero_eyebrow'  => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Frequently asked questions'],
                'hero_headline' => ['label' => 'Headline', 'type' => 'text', 'default' => 'The questions people ask before they call.'],
                'hero_lede'     => ['label' => 'Introduction', 'type' => 'textarea', 'default' => 'If yours is not here, ask us. You do not have to give your name to ask a question.'],
            ],
            'repeaters' => [
                'faqs' => [
                    'label'  => 'Questions',
                    'fields' => ['q' => ['label' => 'Question', 'type' => 'text'], 'a' => ['label' => 'Answer', 'type' => 'textarea']],
                    'default' => [
                        ['q' => 'Is it really free?', 'a' => 'Yes. Counselling, safety planning, referrals and programme access cost you nothing. Donors cover it.'],
                        ['q' => 'Will you tell my husband, my family or my employer?', 'a' => 'No. Nothing that identifies you is shared without your consent. The only exceptions are where the law requires it, or where you, a child or someone else is at serious risk of harm — and where it is safe to, we will tell you first.'],
                        ['q' => 'What do you write down about me?', 'a' => "Only what we need to support you, and only after you agree. Your file is kept locked or password-protected, only the staff working with you can see it, and it is securely destroyed once it is no longer needed.\n\nWhen we report to the people who fund us, we use numbers and anonymised summaries — never your name. [Read our confidentiality policy](/policies#confidentiality)."],
                        ['q' => 'Can I stop at any time?', 'a' => 'Yes. Using our services is voluntary. You can withdraw whenever you want, and your case can be closed at your request. If you come back later, we will be here.'],
                        ['q' => 'Do I have to report to the police?', 'a' => 'No. Many women choose not to, and that is your decision. We will explain what reporting involves so you can decide with full information, and we will support you either way.'],
                        ['q' => 'Do I have to leave my husband?', 'a' => 'No. We do not pressure anyone to leave. Many women come to us while still in the relationship, and we help them stay as safe as possible.'],
                        ['q' => 'What if I go back to him?', 'a' => 'Then we are still here. This is normal and it is not a failure. Come back whenever you are ready.'],
                        ['q' => 'I’m not sure it’s bad enough to count.', 'a' => 'If you are asking that question, you are welcome here. There is no minimum threshold.'],
                        ['q' => 'Do you help men?', 'a' => 'Our programmes are designed for women and children, which is where our clinical expertise and safeguarding structures lie. Men experiencing abuse can contact us and we will refer you to organisations equipped to help.'],
                        ['q' => 'Can I volunteer if I’m a survivor myself?', 'a' => 'Yes, and many of our team are. We ask that you be at a stable point in your own recovery, and we will discuss this openly and supportively during screening.'],
                        ['q' => 'Where do you operate?', 'a' => 'We are based in Lagos and work with women and children across Lagos State. If you are outside Lagos, contact us anyway and we will help you find support near you.'],
                        ['q' => 'How do I make a complaint about Strandfold or someone who works here?', 'a' => 'You can report anything that worries you — including about a staff member or volunteer — and you do not have to give your name. We acknowledge every report within 5 working days, and nobody who raises a concern in good faith will be penalised for it. [How to raise a concern](/policies#complaints).'],
                        ['q' => 'Can someone see that I visited this website?', 'a' => "Possibly. A browser keeps a history of the pages you open, and a shared account or a monitored phone can show more than that.\n\nUse the red **Quick exit** button at the top of any page to leave this site immediately, and open the “Cover your tracks” link for step-by-step instructions."],
                    ],
                ],
            ],
        ],

        /* ---------------------------------------------------------------- */
        'policies' => [
            'label' => 'Policies',
            'file'  => 'policies.html',
            'title' => 'Policies — Strandfold',
            'desc'  => 'How Strandfold Support Foundation safeguards women and children, protects personal information, and handles complaints and whistleblowing.',
            'note'  => 'Public summaries only. Internal documents — intake forms, consent forms, referral sheets, the case management SOP and the shelter partner list — must never be published here.',
            'fields' => [
                'hero_eyebrow'  => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Our policies'],
                'hero_headline' => ['label' => 'Headline', 'type' => 'text', 'default' => 'The rules we hold ourselves to.'],
                'hero_lede'     => ['label' => 'Introduction', 'type' => 'textarea', 'default' => 'Everyone who works with Strandfold — staff, volunteers, consultants, interns, board members and partner representatives — agrees to these policies. These are plain-language summaries. Ask us if you would like the full documents.'],
                'reviewed'      => ['label' => 'Last reviewed', 'type' => 'text', 'default' => '[month, year]', 'help' => 'When the board last reviewed these policies.'],
            ],
            'repeaters' => [
                'policies' => [
                    'label'  => 'Policies',
                    'help'   => 'The anchor is what links across the site point at — changing it breaks those links.',
                    'fields' => [
                        'anchor' => ['label' => 'Anchor id', 'type' => 'text'],
                        'title'  => ['label' => 'Heading', 'type' => 'text'],
                        'body'   => ['label' => 'Content', 'type' => 'textarea'],
                    ],
                    'default' => [
                        ['anchor' => 'safeguarding', 'title' => 'Safeguarding and child protection', 'body' => "Strandfold is committed to a safe environment for every woman and child who uses our services. **We have zero tolerance for abuse, exploitation, neglect, harassment or any other harm.** A child is anyone under 18.\n\n**What we commit to:** safe recruitment and background screening for everyone who works with us; safeguarding orientation for all personnel; strict confidentiality of survivor information; a prompt response to every concern; and compliance with Nigerian and Lagos State protection laws.\n\n**What everyone representing us must do:** treat the people we support with dignity and respect, keep professional boundaries, and avoid any inappropriate physical or emotional relationship. **Nobody acting for Strandfold may ever ask you for gifts, money or favours.**\n\n**Raising a concern:** contact us by phone, WhatsApp or email and ask for our Safeguarding Officer. Every report is documented and investigated confidentially. A breach of this policy can lead to suspension, dismissal or referral to the police."],
                        ['anchor' => 'confidentiality', 'title' => 'Confidentiality and data protection', 'body' => "**We collect only the information we need to support you, and only with your informed consent.**\n\nWhat you tell us is used to provide your support. When we report to the people who fund our work, we use numbers and anonymised summaries — never anything that identifies you.\n\n**When we may have to share information without consent:** where the law requires it, or where you, a child or someone else is at serious risk of harm. Where it is safe to, we will tell you first.\n\n**How records are kept:** paper files in locked cabinets, digital records protected by passwords, and access limited to the staff working with you.\n\n**How long we keep them:** only as long as the law and our work require. Then they are securely destroyed.\n\n**If something goes wrong:** any data breach is reported immediately to our Executive Director and put right promptly."],
                        ['anchor' => 'privacy', 'title' => 'This website and your privacy', 'body' => "This website sets no cookies, runs no analytics or advertising, and loads nothing from any other company’s servers, so no advertising or analytics company learns that you visited.\n\nLike every website, our hosting server keeps a short technical log of requests. It is not used to identify visitors.\n\nIf you send us a message through the contact form, we see only what you type into it, and we do not send an automatic reply.\n\n**Your own device can still show that you were here** — your browser history, a shared account or a monitored phone. The cover-your-tracks guide, linked at the bottom of every page, explains how to clear it."],
                        ['anchor' => 'harassment', 'title' => 'Sexual harassment', 'body' => "Sexual harassment is prohibited in any form, in our workplace and in our programmes.\n\nIt includes unwelcome advances, inappropriate touching, sexual comments, coercion, and any behaviour that creates a hostile environment.\n\nComplaints can be made confidentially to a designated officer and are investigated fairly and promptly. **Retaliation against anyone who complains is prohibited.** Confirmed violations can lead to dismissal and legal action."],
                        ['anchor' => 'conduct', 'title' => 'Code of conduct', 'body' => "Everyone who represents Strandfold, online and offline, must act with integrity and professionalism, treat everyone without discrimination, keep confidential information confidential, avoid conflicts of interest, and use the Foundation’s resources responsibly.\n\n**If anyone claiming to act for Strandfold asks you for money, gifts or favours in return for help, that is not us.** Please report it using the steps below."],
                        ['anchor' => 'complaints', 'title' => 'Complaints and whistleblowing', 'body' => "You can report misconduct, fraud, abuse of power, a safeguarding breach or any unethical behaviour by anyone connected with Strandfold. That includes if you are someone we support.\n\n**How to report:** email our Executive Director at [Executive Director’s email], or write to the Chair of our Board of Trustees at [Board Chair’s address]. **You do not have to give your name.** Anonymous reports are accepted.\n\n**What happens next:** we acknowledge your report within 5 working days, carry out a preliminary review, open a formal investigation where needed, record what we find, and put corrective measures in place.\n\n**Anyone who raises a concern in good faith is protected from retaliation.**"],
                    ],
                ],
            ],
        ],

        /* ---------------------------------------------------------------- */
        'contact' => [
            'label' => 'Contact',
            'file'  => 'contact.html',
            'title' => 'Contact — Strandfold',
            'desc'  => 'Reach Strandfold Support Foundation by phone, WhatsApp, SMS or a private message form. Tell us how and when it is safe to reply to you.',
            'fields' => [
                'hero_eyebrow'  => ['label' => 'Eyebrow', 'type' => 'text', 'default' => 'Contact'],
                'hero_headline' => ['label' => 'Headline', 'type' => 'text', 'default' => 'Reach us however is safest.'],
                'hero_lede'     => ['label' => 'Introduction', 'type' => 'textarea', 'default' => 'You do not have to give your real name. You do not have to explain everything. Tell us how to reply to you safely and we will follow that exactly.'],
                'form_headline' => ['label' => 'Form headline', 'type' => 'text', 'default' => 'A private message.'],
                'form_warning'  => ['label' => 'Before you send', 'type' => 'textarea', 'default' => '**Before you send:** tell us how and when it is safe to reply to you. If it is not safe for us to call or email, say so and we will only message on WhatsApp — or not at all until you contact us again.'],
                'form_note'     => ['label' => 'Form footnote', 'type' => 'textarea', 'default' => 'This form is text only. We do not send an automatic confirmation email, so nothing from us arrives in your inbox unless you asked us to email you. Anything you type here is cleared from the page if you switch away or close the tab.'],
                'form_endpoint' => ['label' => 'Form endpoint URL', 'type' => 'text', 'default' => '', 'help' => 'Where the message form posts. Leave blank and the form tells visitors to use WhatsApp or SMS instead of failing silently. The endpoint must never send a confirmation email to the sender.'],
                'media_note'    => ['label' => 'Media note', 'type' => 'textarea', 'default' => '**We do not connect journalists directly to survivors under any circumstances.**'],
            ],
        ],
    ];
}
