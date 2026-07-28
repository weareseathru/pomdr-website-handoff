<?php
/** Template for the Resources page (WP slug: recources). Ports prototype resources.html. */
get_header();
$img = get_stylesheet_directory_uri() . "/assets/images";
?>
<main id="main-content">

<main id="main">

<header class="page-header">
  <div class="container">
    <h1 class="page-headline">Resources</h1>
    <p class="page-narrative">Caring for a <em>senior dog</em>.</p>
    <p class="page-lead">Practical guides for the questions we hear most often. Mobility, vet care, end-of-life choices, and how to keep a senior dog comfortable.</p>
  </div>
</header>

<section class="section">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:28px">
      <article class="card" style="padding:24px"><div class="eyebrow">Health</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Vet care for senior dogs</h3><p style="color:var(--ink-3);font-size: 16px">Twice-a-year exams, blood panels, dental, pain management. What to ask your vet.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">Mobility</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Stairs, rugs, slippery floors</h3><p style="color:var(--ink-3);font-size: 16px">Small home tweaks that make a huge difference for a dog with arthritis or hind-end weakness.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">Behavior</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Cognitive changes</h3><p style="color:var(--ink-3);font-size: 16px">When a senior dog seems confused at night, or stops settling on a familiar bed.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">Nutrition</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Feeding a senior dog</h3><p style="color:var(--ink-3);font-size: 16px">Calorie counts, joint supplements, and what to do when a dog gets picky.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">End of life</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Quality of life</h3><p style="color:var(--ink-3);font-size: 16px">A framework for the hardest conversations. Hospice options, in-home services, grief support.</p></article>
      <article class="card" style="padding:24px"><div class="eyebrow">Finance</div><h3 style="font-family:var(--font-serif);font-size:22px;margin:8px 0 12px">Help paying for vet care</h3><p style="color:var(--ink-3);font-size: 16px">Local and national funds for medical bills, including our own Helping Paw program.</p></article>
    </div>
  </div>
</section>

<?php
// Curated outside-resource directory, ported from the live POMDR resources page.
// Each entry: title, real URL, and a short description. Links open in a new tab.
$resource_groups = array(
  "Financial Assistance for Pet Guardians" => array(
    array("Assistance Dog Special Allowance Program", "https://www.cdss.ca.gov/assistance-dogs", "A monthly $50 payment for eligible people who use a guide, signal, or service dog for disability-related needs."),
    array("BirchBark Foundation", "https://www.birchbarkfoundation.org", "Financial assistance with veterinary expenses for pet guardians in Monterey and Santa Cruz Counties."),
    array("BluePearl Cares", "https://bluepearlvet.com/bluepearl-cares/", "For BluePearl clients only. Surgery must be done through BluePearl."),
    array("Brown Dog Foundation", "https://www.browndogfoundation.org/ask-for-help", "Apply online with a pre-screening application. If you qualify, a case manager reaches out."),
    array("CareCredit", "https://www.carecredit.com", "A flexible credit card for veterinary care. See if you prequalify without impacting your credit score."),
    array("Free Animal Doctor", "https://freeanimaldoctor.org/", "Crowdfunds individual cases for pets needing expensive procedures, paid directly to the veterinarian."),
    array("Magic Bullet Cure", "https://themagicbulletfund.org/apply/", "For pets diagnosed with cancer who need surgery or chemotherapy. Helps crowdfund with guardian participation."),
    array("The Mosby Foundation", "https://themosbyfoundation.org/apply-for-aid/", "Financial assistance for critically sick and injured dogs."),
    array("Paws 4 A Cure", "https://www.paws4acure.org/askforhelp.php", "Helps with medications, insulin, heart worm treatment, and medical equipment. A one-time grant up to $500."),
    array("The Pet Fund", "https://www.thepetfund.com/about-us", "A national nonprofit dedicated to funding veterinary care for those who cannot afford it."),
    array("Pets Find Help", "https://pets.findhelp.com/", "Search by zip code for food pantries, financial assistance, pet-friendly housing, respite care, and more."),
    array("Pets of the Homeless", "https://petsofthehomeless.org/", "Financial assistance for vet care and pet food for unhoused individuals. Apply by phone."),
    array("SAGE Compassion for Animals", "https://www.sagec4a.org/apply/", "Assistance for immediate life-threatening injuries or illnesses (no exam fees or diagnostics). Serves nine Bay Area counties."),
    array("Santa Cruz SPCA", "https://spcasc.org/dr-jeans-senior-friends/", "Grants up to $500 for low-income seniors with dogs over six in Santa Cruz County, plus two annual free wellness days."),
    array("Saving Gracie", "https://www.saving-gracie.org/angel-fund-apply-for-help/", "For pets with a good prognosis and no pre-existing conditions, once other resources are exhausted."),
    array("Second Chances for Blind Dogs", "https://secondchancesforblinddogs.org/application-for-cocos-eye-care-grant", "Assistance for low-income guardians of dogs who are blind, have impaired sight, or need eye care and surgery."),
    array("Waggle", "https://www.waggle.org/", "Crowdfunds up to $2,000 per case, paid directly to the veterinarian."),
  ),
  "Grief Support" => array(
    array("National Pet Loss Grief Support Hotline", "tel:18662668635", "Toll free at (866) 266-8635. Monday through Thursday 7 to 9pm and Saturday 1 to 3pm Pacific. Messages are returned at other times."),
    array("BirchBark Pet Loss and Grief Support Group", "http://www.birchbarkfoundation.org/pet-loss-grief-support/", "A safe, comforting place for grieving pet families, offered by the BirchBark Foundation of Santa Cruz."),
  ),
  "Check-in Services" => array(
    array("Iamfine", "http://info.iamfine.com", "A daily check-in service that calls to confirm seniors living alone are okay, which can protect both you and your pets."),
    array("Snug", "https://www.snugsafe.com/", "A free app that checks in daily to make sure you are okay and alerts someone if you are not."),
  ),
  "Life-enhancing Products for Senior Animals and People" => array(
    array("KittyKaddy", "http://www.kittykaddy.com/", "Raised pet bowls that make feeding easier for guardians who have difficulty bending or stooping."),
    array("Lucky and Loyal", "https://luckyandloyal.com/", "Custom orthopedic support for dogs, with a vest and sleeves that help maintain muscle strength and natural motion."),
    array("Ruffwear Web Master Harness", "http://www.ruffwear.com/Web-Master-Harness?sc=2&category=1131", "A support harness that helps you assist your dog up and down, useful during rehabilitation."),
    array("Skid Safe", "http://www.ndclean.com/skid-safe-water-based-sealer--finish", "A water-based sealer that makes slippery floors highly slip resistant to reduce fall hazards."),
    array("Toe Grips", "https://www.toegrips.com", "Nonslip nail grips that give senior and special-needs dogs instant traction on hardwood, laminate, and tile."),
  ),
  "Pet Trusts" => array(
    array("Clark Ruggiero Law Office", "https://clarkruggiero.com/", "Creates a free pet trust for people leaving a dog to Peace of Mind Dog Rescue."),
    array("ASPCA Pet Trusts", "https://www.aspca.org/pet-care/pet-planning/frequently-asked-questions", "Frequently asked questions about pet trusts."),
    array("GoodRx", "https://www.goodrx.com/pet-health/pets/pet-in-will-trust", "How to provide for a pet in a will or trust."),
    array("Professor Beyer, Estate Planning for Pets", "http://www.professorbeyer.com/Articles/Animals.html", "Frequently asked questions about pet trusts and estate planning for animals."),
    array("2nd Chance for Pets", "http://www.2ndchance4pets.org/", "A nationwide organization with information and lifetime-care solutions for arranging care for companion animals."),
  ),
  "Books" => array(
    array("When Your Pet Outlives You", "https://www.amazon.com/When-Your-Pet-Outlives-You/dp/0939165449", "Donald Congalton and Charlotte Alexander. A step-by-step guide to protecting animal companions after you die, with personal stories and sample legal documents."),
    array("All My Children Wear Fur Coats", "http://www.legacyforyourpet.com/", "Peggy R. Hoyt. A pet estate-planning book about leaving a legacy for your pet and planning for their future without you."),
    array("Perpetual Care", "https://www.amazon.com/PerPETual-Care-after-Youre-Around/dp/0965250288", "Lisa Rogak. The pros and cons of pet trusts, how to select a caretaker, and how to make a plan that works for everyone."),
  ),
  "Senior Dog Rescues" => array(
    array("Muttville", "http://www.muttville.org/", "A San Francisco nonprofit dedicated to improving the lives of senior dogs through rescue, hospice, and education."),
    array("Old Dog Haven", "http://www.olddoghaven.org/", "A Washington nonprofit providing loving, safe homes for abandoned senior dogs through a large network of people."),
    array("Senior Dog Project", "http://www.srdogs.com/", "A broad listing of agencies that help rehome senior dogs over the age of five."),
    array("Tails of Gray", "http://www.tailsofgray.org", "Dedicated to saving homeless senior dogs, providing quality medical care, and placing them in permanent homes."),
  ),
  "Senior Cat Rescues" => array(
    array("Golden Oldies Cat Rescue", "http://www.gocatrescue.org", "An all-volunteer, foster-based resource and advocate for older cats in Monterey County, with a lifetime commitment to the cats in their care."),
  ),
);
?>

<section class="section" style="background:var(--cream-2);">
  <div class="container">
    <div class="eyebrow">Resource Directory</div>
    <h2 class="section-title">Help from <em>our community.</em></h2>
    <p class="page-lead" style="margin-top:8px;max-width:60ch;">We have compiled resources for senior dogs and senior people. We hope you find this information helpful. Links open in a new tab and lead to outside organizations.</p>

    <?php foreach ( $resource_groups as $group_title => $entries ) : ?>
      <h3 style="font-family:var(--font-serif);font-size:26px;font-weight:500;margin:48px 0 0;"><?php echo esc_html( $group_title ); ?></h3>
      <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;margin-top:20px;">
        <?php foreach ( $entries as $entry ) :
          list( $name, $url, $desc ) = $entry;
          $is_tel = ( strpos( $url, 'tel:' ) === 0 );
        ?>
          <article class="card" style="padding:24px;display:flex;flex-direction:column;gap:10px;">
            <h4 style="font-family:var(--font-serif);font-size:19px;margin:0;">
              <a href="<?php echo esc_url( $url ); ?>"<?php echo $is_tel ? '' : ' target="_blank" rel="noopener noreferrer"'; ?> style="color:var(--blue,#008bb0);text-decoration:none;"><?php echo esc_html( $name ); ?></a>
            </h4>
            <p style="color:var(--ink-3);font-size: 16px;line-height:1.55;margin:0;"><?php echo esc_html( $desc ); ?></p>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<section class="cta-strip">
  <div class="container">
    <h2 class="serif">Need help <em>now</em>?</h2>
    <div class="ctas">
      <a href="/helping-paw/" class="btn btn-primary">Helping Paw Program</a>
      <a href="mailto:info@pomdr.org" class="btn btn-outline">Call Us</a>
    </div>
  </div>
</section>

</main>

</main>
<?php get_footer();
