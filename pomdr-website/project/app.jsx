const { useState: useS, useEffect: useEf } = React;

const TweaksPanel = ({ tweaks, setTweak, onClose }) => {
  const opts = {
    heroLayout: ["fullbleed", "split", "editorial"],
    cardStyle: ["editorial", "minimal", "polaroid"],
    motion: ["subtle", "moderate", "rich"],
  };
  const labels = { heroLayout: "Hero Layout", cardStyle: "Dog Card Style", motion: "Motion Intensity" };
  return (
    <div className="tweaks-panel open">
      <h4>Tweaks <button onClick={onClose}>×</button></h4>
      {Object.keys(opts).map(key => (
        <div className="tweak-row" key={key}>
          <label>{labels[key]}</label>
          <div className="tweak-segments">
            {opts[key].map(v => (
              <button key={v} className={tweaks[key] === v ? 'active' : ''} onClick={() => setTweak(key, v)}>{v}</button>
            ))}
          </div>
        </div>
      ))}
    </div>
  );
};

const App = () => {
  const [tweaks, setTweaks] = useS(window.POMDR_TWEAKS);
  const [editMode, setEditMode] = useS(false);

  useEf(() => {
    document.body.dataset.cardStyle = tweaks.cardStyle;
    document.body.dataset.motion = tweaks.motion;
    document.body.dataset.heroLayout = tweaks.heroLayout;
  }, [tweaks]);

  useEf(() => {
    const handler = (e) => {
      if (!e.data) return;
      if (e.data.type === '__activate_edit_mode') setEditMode(true);
      if (e.data.type === '__deactivate_edit_mode') setEditMode(false);
    };
    window.addEventListener('message', handler);
    window.parent.postMessage({type: '__edit_mode_available'}, '*');
    return () => window.removeEventListener('message', handler);
  }, []);

  const setTweak = (key, val) => {
    const next = { ...tweaks, [key]: val };
    setTweaks(next);
    window.parent.postMessage({type: '__edit_mode_set_keys', edits: { [key]: val }}, '*');
  };

  return (
    <>
      <window.Nav />
      <window.Hero />
      <window.Marquee />
      <window.Adoptables />
      <window.Pillars />
      <window.Mission />
      <window.Programs />
      <window.Impact />
      <window.Tails />
      <window.Events />
      <window.Newsletter />
      <window.Footer />
      {editMode && <TweaksPanel tweaks={tweaks} setTweak={setTweak} onClose={() => setEditMode(false)} />}
    </>
  );
};

ReactDOM.createRoot(document.getElementById('root')).render(<App />);
