const { useState, useEffect, useRef } = React;

// Logo mark
const Logo = () => (
  <a href="index.html" className="logo">
    <div className="logo-mark">P</div>
    <div className="logo-text">
      <div className="name">Peace of Mind</div>
      <div className="sub">Dog Rescue</div>
    </div>
  </a>
);

const Nav = () => {
  const [scrolled, setScrolled] = useState(false);
  useEffect(() => {
    const h = () => setScrolled(window.scrollY > 40);
    window.addEventListener('scroll', h);
    return () => window.removeEventListener('scroll', h);
  }, []);
  return (
    <nav className={`nav ${scrolled ? 'scrolled' : ''}`}>
      <div className="container">
        <div className="nav-inner">
          <Logo />
          <div className="nav-links">
            <a href="#adopt">Adopt</a>
            <a href="#volunteer">Volunteer</a>
            <a href="#helping-paw">Helping Paw</a>
            <a href="#surrender">Surrender</a>
            <a href="#about">About</a>
            <a href="#donate" className="nav-cta">
              Donate
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2.5"><path d="M5 12h14M13 5l7 7-7 7"/></svg>
            </a>
          </div>
        </div>
      </div>
    </nav>
  );
};

// Reveal on scroll
const Reveal = ({ children, delay = 0, as: Tag = 'div', ...rest }) => {
  const ref = useRef(null);
  const [shown, setShown] = useState(false);
  useEffect(() => {
    const el = ref.current; if (!el) return;
    const obs = new IntersectionObserver(([e]) => {
      if (e.isIntersecting) { setShown(true); obs.disconnect(); }
    }, { rootMargin: '-60px' });
    obs.observe(el);
    return () => obs.disconnect();
  }, []);
  return (
    <Tag ref={ref} className={`reveal ${shown ? 'in' : ''}`} style={{transitionDelay: `${delay}ms`}} {...rest}>
      {children}
    </Tag>
  );
};

Object.assign(window, { Logo, Nav, Reveal });
