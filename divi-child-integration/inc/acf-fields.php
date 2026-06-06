<?php
/**
 * ACF Pro field group definitions.
 *
 * All field groups are registered here in PHP so they are version-controlled.
 * If ACF is not active, this file does nothing.
 *
 * CPTs covered:
 *   - pets      (dog records — the most important)
 *   - team      (staff, board, advisory council)
 *   - events    (adoption events, fundraisers, community)
 *   - projects  (campaigns, special initiatives)
 *
 * Status vocabulary (kebab-case, matches theme.json and CSS class names):
 *   available | foster-needed | foster-needed-dated | adoption-pending |
 *   recently-adopted | hospice | courtesy-listing
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
    return;
}

/* =====================================================================
   PETS CPT — dog records
   ===================================================================== */

acf_add_local_field_group( array(
    'key'      => 'group_pomdr_pets',
    'title'    => 'Dog Details',
    'fields'   => array(

        /* ---- Vital stats ---- */
        array(
            'key'   => 'field_pet_breed',
            'label' => 'Breed',
            'name'  => 'breed',
            'type'  => 'text',
            'instructions' => 'e.g. Long-haired Dachshund or Yorkie / Shih Tzu mix',
        ),
        array(
            'key'   => 'field_pet_sex',
            'label' => 'Sex',
            'name'  => 'sex',
            'type'  => 'select',
            'choices' => array(
                'Female'  => 'Female',
                'Male'    => 'Male',
                'Unknown' => 'Unknown',
            ),
            'default_value' => 'Unknown',
        ),
        array(
            'key'   => 'field_pet_age_years',
            'label' => 'Age (years)',
            'name'  => 'age_years',
            'type'  => 'number',
            'min'   => 0,
            'max'   => 30,
            'step'  => 0.5,
            'instructions' => 'Use 0.5 increments for puppies. Prefix with ~ in display when approximate.',
        ),
        array(
            'key'   => 'field_pet_age_approximate',
            'label' => 'Age is approximate',
            'name'  => 'age_approximate',
            'type'  => 'true_false',
            'instructions' => 'If checked, the display will show ~N yrs instead of N yrs.',
        ),
        array(
            'key'   => 'field_pet_weight_lb',
            'label' => 'Weight (lbs)',
            'name'  => 'weight_lb',
            'type'  => 'number',
            'min'   => 1,
            'max'   => 200,
        ),

        /* ---- Status and category ---- */
        array(
            'key'   => 'field_pet_status',
            'label' => 'Status',
            'name'  => 'status',
            'type'  => 'select',
            'choices' => array(
                'available'           => 'Available',
                'foster-needed'       => 'Foster Needed',
                'foster-needed-dated' => 'Foster Needed (with dates)',
                'adoption-pending'    => 'Adoption Pending',
                'recently-adopted'    => 'Recently Adopted',
                'hospice'             => 'Hospice',
                'courtesy-listing'    => 'Courtesy Listing',
            ),
            'default_value' => 'available',
        ),
        array(
            'key'   => 'field_pet_foster_date_start',
            'label' => 'Foster needed from',
            'name'  => 'foster_date_start',
            'type'  => 'date_picker',
            'conditional_logic' => array( array( array(
                'field'    => 'field_pet_status',
                'operator' => '==',
                'value'    => 'foster-needed-dated',
            ) ) ),
        ),
        array(
            'key'   => 'field_pet_foster_date_end',
            'label' => 'Foster needed until',
            'name'  => 'foster_date_end',
            'type'  => 'date_picker',
            'conditional_logic' => array( array( array(
                'field'    => 'field_pet_status',
                'operator' => '==',
                'value'    => 'foster-needed-dated',
            ) ) ),
        ),
        array(
            'key'   => 'field_pet_category',
            'label' => 'Category',
            'name'  => 'category',
            'type'  => 'select',
            'choices' => array(
                'adoptable' => 'Adoptable Dogs',
                'courtesy'  => 'Courtesy Listing',
                'hospice'   => 'Hospice',
            ),
            'default_value' => 'adoptable',
        ),
        array(
            'key'   => 'field_pet_campaign_tag',
            'label' => 'Campaign tag',
            'name'  => 'campaign_tag',
            'type'  => 'select',
            'choices' => array(
                ''                    => 'None',
                'forever-starts-here' => 'Forever Starts Here (long-term dog)',
                'helping-paw-featured'=> 'Helping Paw Featured',
            ),
            'allow_null' => 1,
        ),

        /* ---- Intake metadata ---- */
        array(
            'key'   => 'field_pet_intake_date',
            'label' => 'Intake date',
            'name'  => 'intake_date',
            'type'  => 'date_picker',
            'instructions' => 'When did POMDR take this dog in? Drives the long-term dog calculation.',
        ),
        array(
            'key'   => 'field_pet_legacy_id',
            'label' => 'Legacy ID (old site)',
            'name'  => 'legacy_id',
            'type'  => 'number',
            'instructions' => 'The integer ID from the old peaceofminddogrescue.org dog.php?id=N URL. Used only for 301 redirects. Not shown publicly.',
        ),

        /* ---- Story ---- */
        array(
            'key'   => 'field_pet_story_short',
            'label' => 'Short bio (listing card excerpt)',
            'name'  => 'story_short',
            'type'  => 'textarea',
            'rows'  => 4,
            'maxlength' => 600,
            'instructions' => 'Lead with a specific behavioral detail. 60 to 120 words. No "Meet [Name]" opener. No em dashes. Close with a gentle invitation, never a question.',
            'placeholder' => 'e.g. Pebble does her best thinking from the foot of the couch. At eleven, she is a slow-walker who likes weekly trips to the post office...',
        ),
        array(
            'key'   => 'field_pet_story_long',
            'label' => 'Full bio (detail page)',
            'name'  => 'story_long',
            'type'  => 'wysiwyg',
            'toolbar' => 'basic',
            'instructions' => 'Open with a scene or specific moment. 200 to 400 words. Close with concrete next steps. Sign-off: Andrew Z. No em dashes.',
        ),

        /* ---- Gallery ---- */
        array(
            'key'   => 'field_pet_gallery',
            'label' => 'Photo gallery',
            'name'  => 'gallery',
            'type'  => 'gallery',
            'instructions' => 'Upload additional photos beyond the featured image. First photo in the gallery appears first in the lightbox.',
            'min'   => 0,
            'max'   => 12,
        ),

        /* ---- Override layer ---- */
        array(
            'key'   => 'field_pet_override_tab',
            'label' => 'Override layer',
            'type'  => 'tab',
            'instructions' => 'Fields here override synced source data when set. Leave blank to use the synced value.',
        ),
        array(
            'key'   => 'field_pet_override_name',
            'label' => 'Override: Name',
            'name'  => 'override_name',
            'type'  => 'text',
        ),
        array(
            'key'   => 'field_pet_override_breed',
            'label' => 'Override: Breed',
            'name'  => 'override_breed',
            'type'  => 'text',
        ),
        array(
            'key'   => 'field_pet_override_age',
            'label' => 'Override: Age (years)',
            'name'  => 'override_age_years',
            'type'  => 'number',
        ),

    ),
    'location' => array( array( array(
        'param'    => 'post_type',
        'operator' => '==',
        'value'    => 'pets',
    ) ) ),
    'menu_order'   => 0,
    'position'     => 'normal',
    'style'        => 'default',
    'label_placement' => 'top',
    'active'       => true,
) );


/* =====================================================================
   TEAM CPT — staff, board, advisory council
   ===================================================================== */

acf_add_local_field_group( array(
    'key'   => 'group_pomdr_team',
    'title' => 'Team Member Details',
    'fields' => array(
        array(
            'key'   => 'field_team_job_title',
            'label' => 'Job title',
            'name'  => 'job_title',
            'type'  => 'text',
            'instructions' => 'e.g. Executive Director and Co-founder',
        ),
        array(
            'key'   => 'field_team_department',
            'label' => 'Department',
            'name'  => 'department',
            'type'  => 'select',
            'choices' => array(
                'staff'    => 'Office Staff',
                'clinic'   => 'Clinic Staff',
                'board'    => 'Board of Directors',
                'advisory' => 'Advisory Council',
            ),
        ),
        array(
            'key'   => 'field_team_bio',
            'label' => 'Bio (one line)',
            'name'  => 'bio_short',
            'type'  => 'textarea',
            'rows'  => 2,
            'instructions' => 'A single sentence. Example: Retired teacher, POMDR foster since 2015.',
        ),
        array(
            'key'   => 'field_team_email',
            'label' => 'Public email (optional)',
            'name'  => 'email',
            'type'  => 'email',
            'instructions' => 'Leave blank if not publicly listed.',
        ),
        array(
            'key'   => 'field_team_sort_order',
            'label' => 'Sort order',
            'name'  => 'sort_order',
            'type'  => 'number',
            'instructions' => 'Lower number = appears first. Carie Broecker should be 1.',
        ),
    ),
    'location' => array( array( array(
        'param'    => 'post_type',
        'operator' => '==',
        'value'    => 'team',
    ) ) ),
    'active' => true,
) );


/* =====================================================================
   EVENTS CPT — adoption events, fundraisers, community events
   ===================================================================== */

acf_add_local_field_group( array(
    'key'   => 'group_pomdr_events',
    'title' => 'Event Details',
    'fields' => array(
        array(
            'key'   => 'field_event_date',
            'label' => 'Event date',
            'name'  => 'event_date',
            'type'  => 'date_picker',
            'return_format' => 'Y-m-d',
            'display_format' => 'F j, Y',
        ),
        array(
            'key'   => 'field_event_time',
            'label' => 'Time',
            'name'  => 'event_time',
            'type'  => 'text',
            'instructions' => 'e.g. 11 am to 1:30 pm',
            'placeholder'  => '10 am to noon',
        ),
        array(
            'key'   => 'field_event_location_name',
            'label' => 'Location name',
            'name'  => 'event_location_name',
            'type'  => 'text',
            'instructions' => 'e.g. Wishbone Pet Company',
        ),
        array(
            'key'   => 'field_event_location_address',
            'label' => 'Location address',
            'name'  => 'event_location_address',
            'type'  => 'text',
            'instructions' => 'e.g. 1994 Freedom Blvd, Freedom/Watsonville',
        ),
        array(
            'key'   => 'field_event_type',
            'label' => 'Event type',
            'name'  => 'event_type',
            'type'  => 'select',
            'choices' => array(
                'adoption'    => 'Adoption Event',
                'fundraiser'  => 'Fundraiser',
                'volunteer'   => 'Volunteer',
                'community'   => 'Community',
                'orientation' => 'Volunteer Orientation',
            ),
        ),
        array(
            'key'   => 'field_event_featured',
            'label' => 'Featured event',
            'name'  => 'event_featured',
            'type'  => 'true_false',
            'instructions' => 'Show this event in the hero/featured slot on the events page.',
        ),
    ),
    'location' => array( array( array(
        'param'    => 'post_type',
        'operator' => '==',
        'value'    => 'events',
    ) ) ),
    'active' => true,
) );
