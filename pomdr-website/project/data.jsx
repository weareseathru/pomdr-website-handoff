// Dog placeholder SVG generator - warm tones inspired by brand
const DogPhoto = ({ name, seed = 0, variant = "portrait" }) => {
  const palettes = [
    ["#C9BBD7", "#7F5A9D", "#632F88"],  // purple
    ["#BCD7E4", "#26A1BF", "#008bb0"],  // blue
    ["#f7ddb7", "#d9a870", "#8b5a2b"],  // warm tan
    ["#e8d5c4", "#c9a88a", "#6b4a2b"],  // sepia
    ["#d4c5b0", "#9c8670", "#4a3a28"],  // olive-brown
    ["#cfd9bf", "#84966a", "#3d4a2b"],  // sage
    ["#f0c8b0", "#d4896a", "#8b3a2b"],  // peach
    ["#b8c9d4", "#6a8fa0", "#2b4a5e"],  // slate
  ];
  const p = palettes[seed % palettes.length];
  const id = `g-${seed}-${name.replace(/\W/g,'')}`;
  return (
    <svg viewBox="0 0 300 400" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" style={{width:'100%',height:'100%',display:'block'}}>
      <defs>
        <linearGradient id={id} x1="0" y1="0" x2="0" y2="1">
          <stop offset="0%" stopColor={p[0]} />
          <stop offset="60%" stopColor={p[1]} />
          <stop offset="100%" stopColor={p[2]} />
        </linearGradient>
        <pattern id={`${id}-dots`} width="24" height="24" patternUnits="userSpaceOnUse">
          <circle cx="1" cy="1" r="1" fill="rgba(255,255,255,0.06)" />
        </pattern>
      </defs>
      <rect width="300" height="400" fill={`url(#${id})`} />
      <rect width="300" height="400" fill={`url(#${id}-dots)`} />
      {/* soft vignette */}
      <radialGradient id={`${id}-v`} cx="50%" cy="40%" r="70%">
        <stop offset="60%" stopColor="rgba(0,0,0,0)" />
        <stop offset="100%" stopColor="rgba(0,0,0,0.35)" />
      </radialGradient>
      <rect width="300" height="400" fill={`url(#${id}-v)`} />
      {/* monospace placeholder note */}
      <g fontFamily="ui-monospace, Menlo, monospace" fontSize="9" fill="rgba(255,255,255,0.55)" letterSpacing="1">
        <text x="14" y="22">◐ photo · {name.toLowerCase()}.jpg</text>
        <text x="14" y="388">[placeholder]</text>
      </g>
      {/* big serif name */}
      <text x="150" y="220" textAnchor="middle" fontFamily="'Source Serif 4', serif" fontWeight="400" fontSize="72" fill="rgba(255,255,255,0.92)" fontStyle="italic">{name}</text>
    </svg>
  );
};

const HERO_SLIDES = [
  {
    dog: "Pebble",
    title: ["Every senior deserves", "a soft place to", "land."],
    titleEm: "soft place",
    sub: "Since 2009, POMDR has been the resource and advocate for senior dogs and senior people on California's Central Coast.",
    seed: 3,
    tag: "Pebble · ~12 yrs · Monterey",
  },
  {
    dog: "Sun Bear",
    title: ["Second chances", "come with", "silver muzzles."],
    titleEm: "silver muzzles",
    sub: "We believe every dog deserves a second chance at life, especially the ones the world tends to overlook.",
    seed: 2,
    tag: "Sun Bear · ~12 yrs · Santa Cruz",
  },
  {
    dog: "Oyster",
    title: ["A lifetime", "commitment to", "every dog."],
    titleEm: "commitment",
    sub: "Rescue, foster, adoption, hospice, and education. We walk alongside our dogs and their guardians for life.",
    seed: 5,
    tag: "Oyster · ~13 yrs · San Benito",
  },
];

const DOGS = [
  { name: "Pebble",   age: "~12 yrs", sex: "F", weight: 11, breed: "Long-haired Dachshund",  tags: ["Calm", "Loves naps"],    img: "images/dog1.jpeg",  status: "available",         seed: 0 },
  { name: "Sun Bear", age: "~12 yrs", sex: "M", weight: 11, breed: "Yorkie / Shih Tzu mix",   tags: ["Sunshine", "Gentle"],    img: "uploads/Blythe with Tamagochi (1).jpeg", status: "hospice", seed: 2 },
  { name: "Bixby",    age: "~12 yrs", sex: "F", weight: 10, breed: "Yorkie",                  tags: ["Sweet", "Low-key"],      img: "images/dog4.jpeg",  status: "available",         seed: 3 },
  { name: "Watson",   age: "~12 yrs", sex: "M", weight: 12, breed: "Chihuahua mix",           tags: ["Curious", "Walker"],     img: "images/dog2.jpeg",  status: "available",         seed: 4 },
  { name: "Honeybee", age: "~7 yrs",  sex: "F", weight: 20, breed: "French Bulldog",          tags: ["Snuggler"],              img: "images/dog6.jpeg",  status: "foster_needed",     seed: 5 },
  { name: "Aragorn",  age: "~10 yrs", sex: "M", weight: 20, breed: "Chihuahua mix",           tags: ["Regal", "Affectionate"], img: "images/dog7.jpeg",  status: "adoption_pending",  seed: 6 },
];

const PROGRAMS = [
  {
    title: "Intake & Adoptions",
    href: "adopt.html",
    desc: "Finding senior dogs their forever home through careful matching, foster care, and lifelong support.",
    points: ["Adoptable dogs", "Courtesy listings", "Adoption process", "Adoption events"],
    icon: (
      <svg viewBox="0 0 24 24"><path d="M12 21s-8-5.5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 11-8 11z"/></svg>
    ),
  },
  {
    title: "Helping Paw",
    href: "helping-paw.html",
    desc: "Support for guardians facing hardship. Walking brigade, financial assistance, and temporary fosters.",
    points: ["Walking brigade", "Financial assistance", "Temporary fosters"],
    icon: (
      <svg viewBox="0 0 24 24"><circle cx="5" cy="9" r="2"/><circle cx="12" cy="5" r="2"/><circle cx="19" cy="9" r="2"/><path d="M7 17c0-3 2-5 5-5s5 2 5 5-2 4-5 4-5-1-5-4z"/></svg>
    ),
  },
  {
    title: "Lifetime Care",
    href: "perpetual-care-program.html",
    desc: "A plan for your dog after you. We take responsibility for placement if you no longer can.",
    points: ["Placing your dog", "Lifetime commitment", "Planning ahead"],
    icon: (
      <svg viewBox="0 0 24 24"><path d="M12 2l3 6 6 1-4.5 4 1 6-5.5-3-5.5 3 1-6L3 9l6-1 3-6z"/></svg>
    ),
  },
];

const TAILS = [
  {
    featured: true,
    quote: "Rosie walked into our home unsure, and two weeks later she was napping at my feet like she'd always been here. Thank you for trusting us with her golden years.",
    name: "The Alvarez Family",
    city: "Pacific Grove, CA",
    dog: "Rosie",
    dogSeed: 2,
  },
  {
    quote: "They didn't just match us with a dog. They matched us with a companion who understood quiet mornings.",
    name: "Eleanor W.",
    city: "Carmel",
    dog: "Finn",
    dogSeed: 4,
  },
  {
    quote: "POMDR stayed in touch long after adoption day. That's what a lifetime commitment looks like.",
    name: "David & Marta",
    city: "Santa Cruz",
    dog: "Biscuit",
    dogSeed: 6,
  },
];

const EVENTS = [
  { month: "May", day: "03", title: "Pups on the Promenade", type: "Adoption Event", time: "10 am – 2 pm", where: "Pacific Grove" },
  { month: "May", day: "17", title: "Senior Supper Fundraiser", type: "Fundraiser", time: "5:30 – 8 pm", where: "Carmel Valley" },
  { month: "Jun", day: "01", title: "Volunteer Orientation", type: "Volunteer", time: "10 am to noon", where: "POMDR HQ" },
  { month: "Jun", day: "14", title: "Doggy Day Out Walk", type: "Community", time: "9 – 11 am", where: "Lovers Point" },
];

const STATS = [
  {
    num: "3,200", sym: "+", lbl: "Senior dogs rescued", desc: "Since 2009 across the Central Coast",
    story: "From the very first scared pup in 2009 to the gray-muzzled friend you might meet today, every name is remembered, every story counted.",
    img: "images/dog3.jpeg",
    imgAlt: "A senior rescue dog looking calmly into the camera",
  },
  {
    num: "16", sym: "yrs", lbl: "Of lifetime commitment", desc: "To every dog that comes to us",
    story: "Founded by people who refused to look away from the senior dog at the back of the kennel. Sixteen years on, we still don't.",
    img: "uploads/Pat And Zeta.jpeg",
    imgAlt: "Pat with Zeta, a senior dog she adopted through POMDR",
  },
  {
    num: "98", sym: "%", lbl: "Adoption success rate", desc: "Through careful, patient matching",
    story: "Patience makes the match. We take the time to learn each dog so the family who takes them home knows exactly who they are getting.",
    img: "uploads/Hazel HT.jpeg",
    imgAlt: "Hazel napping in her new home after adoption",
  },
  {
    num: "1,800", sym: "+", lbl: "Active volunteers", desc: "Fostering, walking, and caring",
    story: "Foster families, transport drivers, dog walkers, photographers, vet techs. Every Saturday morning, a small army shows up for senior dogs.",
    img: "uploads/Nancy helping paw Finnegan, walker Jane (1).jpeg",
    imgAlt: "Volunteer Jane walking Finnegan as part of the Helping Paw program",
  },
];

const VIDEOS = [
  {
    title: "CNN Heroes, POMDR",
    year: 2022,
    url: "https://www.cnn.com/2022/07/28/us/dog-rescue-aging-california-seniors",
    desc: "CNN recognized POMDR as a Hero organization for its work connecting senior dogs with senior people across California's Central Coast.",
    featured: true,
  },
  {
    title: "This is Our Message to You",
    year: 2026,
    url: "https://youtu.be/B7RQI4beRZU",
    desc: "Our 2026 message to the Central Coast community about what POMDR stands for.",
    featured: false,
  },
  {
    title: "Take a Chance on POMDR",
    year: 2025,
    url: "https://youtu.be/HVv8rEJjnvs",
    desc: "An invitation to learn who we are and why we do this work, told through the dogs themselves.",
    featured: false,
  },
];

const HELPING_PAW = {
  eyebrow: "Helping Paw Program",
  title: "Keeping seniors and their dogs",
  titleEm: "together.",
  desc: "When a guardian faces hardship, we step in. Walking brigades, financial assistance, temporary foster care. Whatever it takes to keep a senior person and their dog together as long as possible.",
  services: [
    {
      title: "Walking Brigade",
      desc: "Volunteers walk dogs for seniors who can no longer do it themselves, whether due to medical recovery, mobility limits, or a difficult stretch.",
    },
    {
      title: "Financial Assistance",
      desc: "Up to $750 for urgent veterinary care, and up to $2,750 for emergency major medical needs. Financial need required; all pets must be spayed or neutered.",
    },
    {
      title: "Temporary Foster",
      desc: "When a guardian is hospitalized or needs recovery time, POMDR fosters their dog and returns them when the guardian is ready.",
    },
  ],
  photo: "uploads/Nancy helping paw Finnegan, walker Jane (1).jpeg",
  photoAlt: "POMDR volunteer Jane walking Finnegan as part of the Helping Paw walking brigade",
  applyUrl: "helping-paw.html",
  donateUrl: "donate.html",
};

Object.assign(window, { DogPhoto, HERO_SLIDES, DOGS, PROGRAMS, TAILS, EVENTS, STATS, VIDEOS, HELPING_PAW });
