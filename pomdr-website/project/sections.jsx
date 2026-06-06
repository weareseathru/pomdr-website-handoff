const { useState: uS, useEffect: uE, useRef: uR } = React;

/* ============== HERO ============== */
const Hero = () => {
  const [idx, setIdx] = uS(0);
  const slides = window.HERO_SLIDES;

  uE(() => {
    const t = setTimeout(() => setIdx((i) => (i + 1) % slides.length), 6000);
    return () => clearTimeout(t);
  }, [idx]);

  const prev = () => setIdx((i) => (i - 1 + slides.length) % slides.length);
  const next = () => setIdx((i) => (i + 1) % slides.length);

  return (
    <div className="hero-outer">
      <section className="hero" aria-label="Featured dogs slideshow">
        {slides.map((s, i) => (
          <div key={i} className={`hero-slide ${i === idx ? 'active' : ''}`} aria-hidden={i !== idx}>
            <div className="hero-image">
              <window.DogPhoto name={s.dog} seed={s.seed} />
            </div>
          </div>
        ))}

        {/* Soft white gradient at bottom, replaces dark color scrim */}
        <div className="hero-gradient-bottom" aria-hidden="true" />

        {/* Prominent side arrows */}
        <button className="hero-arrow hero-arrow--prev" onClick={prev} aria-label="Previous dog">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" aria-hidden="true"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </button>
        <button className="hero-arrow hero-arrow--next" onClick={next} aria-label="Next dog">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </button>

        {/* Position dots */}
        <div className="hero-dots" role="tablist" aria-label="Slideshow navigation">
          {slides.map((s, i) => (
            <button
              key={i}
              className={`hero-dot ${i === idx ? 'active' : ''}`}
              onClick={() => setIdx(i)}
              role="tab"
              aria-selected={i === idx}
              aria-label={`${s.dog}, slide ${i + 1} of ${slides.length}`}
            />
          ))}
        </div>
      </section>

      {/* Text lives BELOW the image, not overlaid on it */}
      <div className="hero-caption">
        <div className="container">
          <p className="hero-caption-tag" key={`tag-${idx}`}>{slides[idx].tag}</p>
          <h1 className="hero-title" key={`title-${idx}`}>
            {slides[idx].title.map((line, i) => (
              <span key={i} className="hero-title-line">
                {line.includes(slides[idx].titleEm)
                  ? <>{line.split(slides[idx].titleEm)[0]}<em>{slides[idx].titleEm}</em>{line.split(slides[idx].titleEm)[1]}</>
                  : line}
              </span>
            ))}
          </h1>
          <p className="hero-sub" key={`sub-${idx}`}>{slides[idx].sub}</p>
          <div className="hero-ctas">
            <a href="adopt.html" className="btn btn-primary">
              See our dogs
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </a>
            <a href="donate.html" className="btn btn-outline">
              Support our mission
            </a>
          </div>
        </div>
      </div>
    </div>
  );
};

/* ============== ADOPTABLES ============== */
const Adoptables = () => {
  const [liked, setLiked] = uS(new Set());
  const toggle = (n) => {
    const s = new Set(liked);
    s.has(n) ? s.delete(n) : s.add(n);
    setLiked(s);
  };
  return (
    <section className="section adoptables" id="adopt">
      <div className="container">
        <window.Reveal>
          <div className="section-header">
            <span className="eyebrow">Adoptable Dogs</span>
            <h2 className="section-title">Find the pups waiting for <em>their next chapter.</em></h2>
            <p className="section-lead">Each of these seniors has a full heart, a few grey hairs, and a story that's not finished yet.</p>
          </div>
        </window.Reveal>
        <div className="dogs-grid">
          {window.DOGS.map((d, i) => (
            <window.Reveal key={d.name} delay={i * 60}>
              <div className="dog-card">
                <div className="photo-wrap">
                  <div className="dog-photo"><window.DogPhoto name={d.name} seed={d.seed} /></div>
                  <div className="badge">Adoptable</div>
                  <button className={`heart ${liked.has(d.name) ? 'active' : ''}`} onClick={() => toggle(d.name)} aria-label="Favorite">
                    <svg viewBox="0 0 24 24"><path d="M12 21s-8-5.5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 11-8 11z"/></svg>
                  </button>
                </div>
                <div className="info">
                  <div className="name">{d.name}<span className="age">{d.age}</span></div>
                  <div style={{fontSize:14,color:'var(--ink-3)'}}>{d.breed}</div>
                  <div className="tags">{d.tags.map(t => <span key={t} className="tag">{t}</span>)}</div>
                  <div className="cta-row">
                    <span>View Profile</span>
                    <span className="arrow"><svg viewBox="0 0 24 24"><path d="M5 12h14M13 5l7 7-7 7"/></svg></span>
                  </div>
                </div>
              </div>
            </window.Reveal>
          ))}
          <window.Reveal delay={window.DOGS.length * 60}>
            <div className="dog-card see-more">
              <div>
                <div className="see-more-num">24+</div>
                <div className="see-more-label">more pups looking for their people</div>
                <div className="arrow-big">
                  <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </div>
              </div>
            </div>
          </window.Reveal>
        </div>
      </div>
    </section>
  );
};

/* ============== PILLARS ============== */
const Pillars = () => {
  const pillars = [
    { num: "01", label: "Adopt", title: "Open your home.", desc: "Match with a senior pup whose quiet mornings suit yours.", seed: 0 },
    { num: "02", label: "Donate", title: "Fuel the work.", desc: "Your gift funds medical care, foster stipends and a lifetime promise.", seed: 2 },
    { num: "03", label: "Volunteer", title: "Walk with us.", desc: "Foster, transport, write bios. Every hour makes a difference.", seed: 5 },
  ];
  return (
    <section className="section pillars">
      <div className="container">
        <div className="pillars-grid">
          {pillars.map((p, i) => (
            <window.Reveal key={p.num} delay={i * 80}>
              <a className="pillar" href={`#${p.label.toLowerCase()}`} style={{'--tilt': 0}}>
                <div className="pillar-num">{p.num}</div>
                <div style={{position:'absolute',inset:0,zIndex:0}}>
                  <window.DogPhoto name={p.label} seed={p.seed} />
                </div>
                <div style={{position:'absolute',inset:0,background:'linear-gradient(180deg, transparent 30%, rgba(22,32,43,0.85) 100%)',zIndex:1}}/>
                <div style={{position:'relative',zIndex:2}}>
                  <div style={{fontSize:12,letterSpacing:'0.2em',textTransform:'uppercase',fontWeight:600,marginBottom:12,opacity:0.9}}>{p.label}</div>
                  <h3>{p.title}</h3>
                  <p>{p.desc}</p>
                  <span className="pillar-cta">
                    Learn more
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                  </span>
                </div>
              </a>
            </window.Reveal>
          ))}
        </div>
      </div>
    </section>
  );
};

/* ============== MISSION ============== */
const Mission = () => (
  <section className="mission" id="about">
    <div className="container">
      <div className="mission-inner">
        <window.Reveal>
          <div className="mission-visual">
            <window.DogPhoto name="Together" seed={1} />
            <div className="float float-1">
              <div className="ic purple">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><path d="M12 21s-8-5.5-8-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 5.5-8 11-8 11z"/></svg>
              </div>
              <div>
                <div className="num">Since 2009</div>
                <div className="lbl">A promise kept</div>
              </div>
            </div>
            <div className="float float-2">
              <div className="ic">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2"><circle cx="12" cy="12" r="9"/><path d="M12 6v6l4 2"/></svg>
              </div>
              <div>
                <div className="num">Central Coast</div>
                <div className="lbl">Monterey · Santa Cruz · San Benito</div>
              </div>
            </div>
          </div>
        </window.Reveal>
        <window.Reveal delay={120}>
          <div>
            <span className="eyebrow">Our Mission</span>
            <h2 className="mission-title">Who will care for your dog if <em>you no longer can?</em></h2>
            <p className="mission-text">Our mission is to be a resource and advocate for senior dogs and senior people on California's Central Coast. We focus on helping dogs and people from Monterey, Santa Cruz and San Benito counties, through rescue, foster, adoption, hospice and education.</p>
            <div style={{display:'flex',gap:12,flexWrap:'wrap'}}>
              <a href="surrender.html" className="btn btn-primary">Surrender options</a>
              <a href="perpetual-care-program.html" className="btn btn-outline">Lifetime care</a>
            </div>
            <div className="mission-sig">
              <div className="avatar"><window.DogPhoto name="Carie" seed={0} /></div>
              <div className="who"><strong>Carie Broecker</strong><span>Executive Director and Co-founder</span></div>
            </div>
          </div>
        </window.Reveal>
      </div>
    </div>
  </section>
);

/* ============== PROGRAMS ============== */
const Programs = () => (
  <section className="section programs" id="helping-paw">
    <div className="container">
      <window.Reveal>
        <div className="section-header">
          <span className="eyebrow">Our Programs</span>
          <h2 className="section-title">A <em>lifetime commitment</em>, not a paperwork kind.</h2>
          <p className="section-lead">Three intertwined programs, one promise: we walk alongside our dogs and their people for life.</p>
        </div>
      </window.Reveal>
      <div className="programs-grid">
        {window.PROGRAMS.map((p, i) => (
          <window.Reveal key={p.title} delay={i * 80}>
            <div className="program-card">
              <div className="program-icon">{p.icon}</div>
              <h3>{p.title}</h3>
              <p>{p.desc}</p>
              <ul>{p.points.map(x => <li key={x}>{x}</li>)}</ul>
              <a href={p.href} className="learn">Explore {p.title}</a>
            </div>
          </window.Reveal>
        ))}
      </div>
    </div>
  </section>
);

/* ============== IMPACT ============== */
const Impact = () => (
  <section className="impact">
    <div className="container">
      <window.Reveal>
        <div className="section-header left">
          <span className="eyebrow">Our Impact</span>
          <h2 className="section-title">Quiet numbers. <em>Loud love.</em></h2>
          <p className="section-lead">Every rescue, every adoption, every quiet walk adds up to something the numbers can only partly hold.</p>
        </div>
      </window.Reveal>
      <div className="impact-grid">
        {window.STATS.map((s, i) => (
          <window.Reveal key={s.lbl} delay={i * 80}>
            <div className="stat">
              <div className="num">{s.num}<span className="sym">{s.sym}</span></div>
              <div className="lbl">{s.lbl}</div>
              <div className="desc">{s.desc}</div>
            </div>
          </window.Reveal>
        ))}
      </div>
    </div>
  </section>
);

/* ============== HAPPY TAILS ============== */
const Tails = () => (
  <section className="tails">
    <div className="container">
      <window.Reveal>
        <div className="section-header">
          <span className="eyebrow">Happy Tails</span>
          <h2 className="section-title">Stories from <em>forever families.</em></h2>
        </div>
      </window.Reveal>
      <div className="tails-grid">
        {window.TAILS.map((t, i) => (
          <window.Reveal key={i} delay={i * 80}>
            <div className={`tail-card ${t.featured ? 'featured' : ''}`}>
              <div className="quote-mark">"</div>
              <blockquote>{t.quote}</blockquote>
              <div className="person">
                <div className="av"><window.DogPhoto name={t.dog} seed={t.dogSeed} /></div>
                <div>
                  <strong>{t.name}</strong>
                  <span>adopted {t.dog} · {t.city}</span>
                </div>
              </div>
            </div>
          </window.Reveal>
        ))}
      </div>
    </div>
  </section>
);

/* ============== EVENTS ============== */
const Events = () => (
  <section className="events">
    <div className="container">
      <window.Reveal>
        <div className="section-header left" style={{display:'flex',justifyContent:'space-between',alignItems:'flex-end',maxWidth:'none',marginBottom:40}}>
          <div style={{maxWidth:560}}>
            <span className="eyebrow" style={{color:'var(--purple)'}}>Upcoming</span>
            <h2 className="section-title">Events & <em>gatherings.</em></h2>
          </div>
          <a href="events.html" className="btn btn-outline">View full calendar</a>
        </div>
      </window.Reveal>
      <div className="events-layout">
        <window.Reveal>
          <div className="event-list">
            {window.EVENTS.map((e, i) => (
              <div className="event-row" key={i}>
                <div className="event-date">
                  <div className="month">{e.month}</div>
                  <div className="day">{e.day}</div>
                </div>
                <div>
                  <div className="event-title">{e.title}</div>
                  <div className="event-meta">
                    <span>⏱ {e.time}</span>
                    <span>◎ {e.where}</span>
                    <span>· {e.type}</span>
                  </div>
                </div>
                <div className="arrow-sm">
                  <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
                </div>
              </div>
            ))}
          </div>
        </window.Reveal>
        <window.Reveal delay={120}>
          <div className="events-feature">
            <div style={{position:'absolute',inset:0,zIndex:0}}>
              <window.DogPhoto name="Gala" seed={1} />
            </div>
            <div className="tag">★ Featured · May 17</div>
            <h3>Senior Supper. An evening for our seniors, by our friends.</h3>
            <div className="details">
              <span>5:30 – 8 pm</span>
              <span>Carmel Valley Ranch</span>
            </div>
            <a href="volunteer.html" className="btn btn-light" style={{width:'fit-content'}}>
              Reserve your seat
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </a>
          </div>
        </window.Reveal>
      </div>
    </div>
  </section>
);

/* ============== NEWSLETTER ============== */
const Newsletter = () => {
  const [email, setEmail] = uS("");
  const [sent, setSent] = uS(false);
  return (
    <section className="newsletter">
      <div className="container">
        <div>
          <span className="eyebrow">Stay in the loop</span>
          <h2>Sweet stories, happy tails and <em>good news</em> in your inbox.</h2>
          <p>One thoughtful email a month. Adoption updates, events, and a little sunshine from the pups.</p>
        </div>
        <form className="newsletter-form" onSubmit={(e) => { e.preventDefault(); setSent(true); }}>
          <label>Newsletter signup</label>
          <div className="nform-row">
            <input type="email" placeholder="your@email.com" value={email} onChange={e => setEmail(e.target.value)} required />
            <button type="submit">{sent ? "✓ Subscribed" : "Subscribe"}</button>
          </div>
          <div className="fine">We respect your inbox. Unsubscribe anytime.</div>
        </form>
      </div>
    </section>
  );
};

/* ============== FOOTER ============== */
const Footer = () => (
  <footer className="footer" id="donate">
    <div className="container">
      <div className="footer-top">
        <div className="footer-brand">
          <window.Logo />
          <h4>Helping senior dogs and senior people since 2009.</h4>
          <p>A 501(c)(3) nonprofit serving Monterey, Santa Cruz and San Benito counties.</p>
          <div className="footer-contact">
            <div className="footer-contact-label">Adopt or Donate</div>
            <div className="footer-contact-row">
              <a href="tel:+18317189122" className="footer-contact-item">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.81 19.79 19.79 0 01.01 1.18a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L6.91 6.91a16 16 0 006.18 6.18l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7a2 2 0 011.72 2z"/></svg>
                <span>(831) 718-9122</span>
              </a>
              <span className="footer-contact-sep" aria-hidden="true">·</span>
              <a href="mailto:info@peaceofminddogrescue.org" className="footer-contact-item">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                <span>info@peaceofminddogrescue.org</span>
              </a>
              <span className="footer-contact-sep" aria-hidden="true">·</span>
              <span className="footer-contact-item">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                <span>Pacific Grove, CA</span>
              </span>
            </div>
          </div>
        </div>
        <div>
          <h5>Adopt</h5>
          <ul>
            <li><a href="adopt.html">Adoptable Dogs</a></li>
            <li><a href="courtesy-listings.html">Courtesy Listings</a></li>
            <li><a href="adopted.html">Recently Adopted</a></li>
            <li><a href="events.html">Adoption Events</a></li>
            <li><a href="process.html">Adoption Process</a></li>
          </ul>
        </div>
        <div>
          <h5>Volunteer</h5>
          <ul>
            <li><a href="volunteer-application.html">Application</a></li>
            <li><a href="foster.html">Foster Needs</a></li>
            <li><a href="volunteer.html">Opportunities</a></li>
          </ul>
          <h5 style={{marginTop:28}}>Surrender</h5>
          <ul>
            <li><a href="surrender.html">Placing Your Dog</a></li>
            <li><a href="perpetual-care-program.html">Lifetime Care</a></li>
          </ul>
        </div>
        <div>
          <h5>About Us</h5>
          <ul>
            <li><a href="about.html#team">Our Team</a></li>
            <li><a href="donate.html">Ways to Give</a></li>
            <li><a href="benefit-shop.html">Benefit Shop</a></li>
            <li><a href="contact.html">Contact</a></li>
          </ul>
        </div>
        <div>
          <h5>Follow</h5>
          <div className="socials">
            <a href="https://www.instagram.com/peace.of.mind.dog.rescue/" target="_blank" rel="noopener" aria-label="Instagram"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.2c3.2 0 3.6 0 4.8.1 1.2.1 1.9.2 2.3.4.6.2 1 .5 1.5 1s.8.9 1 1.5c.2.4.3 1.1.4 2.3.1 1.2.1 1.6.1 4.8s0 3.6-.1 4.8c-.1 1.2-.2 1.9-.4 2.3-.2.6-.5 1-1 1.5s-.9.8-1.5 1c-.4.2-1.1.3-2.3.4-1.2.1-1.6.1-4.8.1s-3.6 0-4.8-.1c-1.2-.1-1.9-.2-2.3-.4-.6-.2-1-.5-1.5-1s-.8-.9-1-1.5c-.2-.4-.3-1.1-.4-2.3C2.2 15.6 2.2 15.2 2.2 12s0-3.6.1-4.8c.1-1.2.2-1.9.4-2.3.2-.6.5-1 1-1.5s.9-.8 1.5-1c.4-.2 1.1-.3 2.3-.4C8.4 2.2 8.8 2.2 12 2.2zm0 1.8c-3.1 0-3.5 0-4.7.1-1 .1-1.6.2-1.9.3-.5.2-.8.4-1.1.7-.3.3-.5.6-.7 1.1-.1.3-.2.9-.3 1.9-.1 1.2-.1 1.6-.1 4.7s0 3.5.1 4.7c.1 1 .2 1.6.3 1.9.2.5.4.8.7 1.1.3.3.6.5 1.1.7.3.1.9.2 1.9.3 1.2.1 1.6.1 4.7.1s3.5 0 4.7-.1c1-.1 1.6-.2 1.9-.3.5-.2.8-.4 1.1-.7.3-.3.5-.6.7-1.1.1-.3.2-.9.3-1.9.1-1.2.1-1.6.1-4.7s0-3.5-.1-4.7c-.1-1-.2-1.6-.3-1.9-.2-.5-.4-.8-.7-1.1-.3-.3-.6-.5-1.1-.7-.3-.1-.9-.2-1.9-.3-1.2-.1-1.6-.1-4.7-.1zm0 3.1a4.9 4.9 0 1 1 0 9.8 4.9 4.9 0 0 1 0-9.8zm0 8.1a3.2 3.2 0 1 0 0-6.4 3.2 3.2 0 0 0 0 6.4zm5-8.3a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2z"/></svg></a>
            <a href="https://www.facebook.com/POMDR/" target="_blank" rel="noopener" aria-label="Facebook"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M13.5 21v-8h2.7l.4-3.2h-3.1V7.7c0-.9.3-1.6 1.6-1.6h1.7V3.2c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.4-4 4v2.7H7.6V13h2.8v8h3.1z"/></svg></a>
            <a href="https://www.tiktok.com/@peace.of.mind.dog.rescue/" target="_blank" rel="noopener" aria-label="TikTok"><svg viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1V9.01a6.33 6.33 0 00-.79-.05 6.34 6.34 0 00-6.34 6.34 6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.33-6.34V8.69a8.18 8.18 0 004.78 1.52V6.76a4.85 4.85 0 01-1.01-.07z"/></svg></a>
            <a href="https://www.youtube.com/user/PeaceOfMindDogRescue" target="_blank" rel="noopener" aria-label="YouTube"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.6 7.2c-.2-.9-.9-1.6-1.8-1.8C18.3 5 12 5 12 5s-6.3 0-7.8.4c-.9.2-1.6.9-1.8 1.8C2 8.7 2 12 2 12s0 3.3.4 4.8c.2.9.9 1.6 1.8 1.8 1.5.4 7.8.4 7.8.4s6.3 0 7.8-.4c.9-.2 1.6-.9 1.8-1.8.4-1.5.4-4.8.4-4.8s0-3.3-.4-4.8zM10 15V9l5.2 3L10 15z"/></svg></a>
            <a href="https://www.linkedin.com/company/peace-of-mind-dog-rescue/" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" aria-hidden="true" fill="currentColor"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2"/></svg></a>
          </div>
          <div style={{marginTop:28}}>
            <h5>Tax-deductible</h5>
            <div style={{fontSize:13,opacity:0.7}}>EIN 27-1154816</div>
          </div>
        </div>
      </div>
      <div className="footer-bottom">
        <div>© {new Date().getFullYear()} Peace of Mind Dog Rescue. 501(c)(3) nonprofit. EIN 27-1154816.</div>
        <div style={{display:'flex',gap:20}}>
          <a href="privacy.html">Privacy</a><a href="terms.html">Terms</a><a href="contact.html">Contact</a>
        </div>
      </div>
    </div>
  </footer>
);

/* ============== VIDEOS ============== */
const Videos = () => (
  <section className="section videos" id="videos">
    <div className="container">
      <window.Reveal>
        <div className="section-header">
          <span className="eyebrow">POMDR in the World</span>
          <h2 className="section-title">Hear the <em>stories directly.</em></h2>
          <p className="section-lead">From a CNN Hero award to letters from the dogs themselves, this is POMDR on screen.</p>
        </div>
      </window.Reveal>
      <div className="videos-grid">
        {window.VIDEOS.map((v, i) => (
          <window.Reveal key={v.title} delay={i * 80}>
            <a
              href={v.url}
              target="_blank"
              rel="noopener noreferrer"
              className={`video-card${v.featured ? ' featured' : ''}`}
              aria-label={`Watch ${v.title} (opens in new tab)`}
            >
              <div className="video-thumb">
                <div className="video-play-btn" aria-hidden="true">
                  <svg viewBox="0 0 24 24" fill="white" aria-hidden="true"><path d="M5 3l14 9-14 9z"/></svg>
                </div>
                {v.featured && <span className="video-badge">CNN Heroes</span>}
              </div>
              <div className="video-info">
                <span className="video-year">{v.year}</span>
                <h3>{v.title}</h3>
                <p>{v.desc}</p>
              </div>
            </a>
          </window.Reveal>
        ))}
      </div>
      <div className="videos-footer">
        <a href="videos.html" className="btn btn-outline">
          Watch all 18 videos
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5" aria-hidden="true"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
        </a>
      </div>
    </div>
  </section>
);

/* ============== HELPING PAW (expanded) ============== */
const HelpingPaw = () => {
  const hp = window.HELPING_PAW;
  return (
    <section className="section helping-paw-expanded" id="helping-paw-detail">
      <div className="container">
        <div className="hp-inner">
          <window.Reveal>
            <div className="hp-photo">
              <img src={hp.photo} alt={hp.photoAlt} loading="lazy" />
            </div>
          </window.Reveal>
          <window.Reveal delay={100}>
            <div className="hp-content">
              <span className="eyebrow">{hp.eyebrow}</span>
              <h2 className="section-title">
                {hp.title} <em>{hp.titleEm}</em>
              </h2>
              <p style={{fontSize:19, color:'var(--ink-2)', maxWidth:'48ch', margin:'0 0 32px'}}>{hp.desc}</p>
              <div className="hp-services">
                {hp.services.map(s => (
                  <div key={s.title} className="hp-service">
                    <h4>{s.title}</h4>
                    <p>{s.desc}</p>
                  </div>
                ))}
              </div>
              <div className="hp-ctas">
                <a href={hp.applyUrl} className="btn btn-primary">Apply for Helping Paw</a>
                <a href={hp.donateUrl} className="btn btn-outline">Donate to the Fund</a>
              </div>
            </div>
          </window.Reveal>
        </div>
      </div>
    </section>
  );
};

Object.assign(window, { Hero, Adoptables, Pillars, Mission, Programs, HelpingPaw, Impact, Tails, Events, Newsletter, Videos, Footer });
