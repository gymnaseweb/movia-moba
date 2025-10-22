function initMutliFilterSetupMultiMatch(){

  const transitionDelay = 300;
  const groups = [...document.querySelectorAll('[data-filter-group]')];

  groups.forEach(group => {
    const targetMatch = (group.getAttribute('data-filter-target-match') || 'multi').trim().toLowerCase(); // 'single' | 'multi'
    const nameMatch   = (group.getAttribute('data-filter-name-match')   || 'multi').trim().toLowerCase(); // 'single' | 'multi'

    const buttons = [...group.querySelectorAll('[data-filter-target]')];
    const items   = [...group.querySelectorAll('[data-filter-name]')];

    // Collect tokens from children if present
    items.forEach(item => {
      const collectors = item.querySelectorAll('[data-filter-name-collect]');
      if (!collectors.length) return;
      const seen = new Set(), tokens = [];
      collectors.forEach(c => {
        const v = (c.getAttribute('data-filter-name-collect') || '').trim().toLowerCase();
        if (v && !seen.has(v)) {
          seen.add(v);
          tokens.push(v);
        }
      });
      if (tokens.length) item.setAttribute('data-filter-name', tokens.join(' '));
    });

    // Cache item tokens
    const itemTokens = new Map();
    items.forEach(el => {
      const raw = (el.getAttribute('data-filter-name') || '').trim().toLowerCase();
      const tokens = raw ? raw.split(/\s+/).filter(Boolean) : [];
      itemTokens.set(el, new Set(tokens));
    });

    const setItemState = (el, on) => {
      const next = on ? 'active' : 'not-active';
      if (el.getAttribute('data-filter-status') !== next) {
        el.setAttribute('data-filter-status', next);
        el.setAttribute('aria-hidden', on ? 'false' : 'true');
      }
    };

    const setButtonState = (btn, on) => {
      const next = on ? 'active' : 'not-active';
      if (btn.getAttribute('data-filter-status') !== next) {
        btn.setAttribute('data-filter-status', next);
        btn.setAttribute('aria-pressed', on ? 'true' : 'false');
      }
    };

    // Active tags model
    let activeTags = targetMatch === 'single' ? null : new Set(['all']);

    const hasRealActive = () => {
      if (targetMatch === 'single') return activeTags !== null;
      return activeTags.size > 0 && !activeTags.has('all');
    };

    const resetAll = () => {
      if (targetMatch === 'single') {
        activeTags = null;
      } else {
        activeTags.clear();
        activeTags.add('all');
      }
    };

    // Matching logic
    const itemMatches = (el) => {
      if (!hasRealActive()) return true;
      const tokens = itemTokens.get(el);

      if (targetMatch === 'single') {
        return tokens.has(activeTags);
      } else {
        const selected = [...activeTags];
        if (nameMatch === 'single') {
          // AND logic: must contain all selected
          for (let i = 0; i < selected.length; i++) {
            if (!tokens.has(selected[i])) return false;
          }
          return true;
        } else {
          // OR logic: must contain any selected
          for (let i = 0; i < selected.length; i++) {
            if (tokens.has(selected[i])) return true;
          }
          return false;
        }
      }
    };

    const paint = (rawTarget) => {
      const target = (rawTarget || '').trim().toLowerCase();
      if ((target === 'all' || target === 'reset') && !hasRealActive()) return;

      if (target === 'all' || target === 'reset') {
        resetAll();
      } else if (targetMatch === 'single') {
        activeTags = target;
      } else {
        if (activeTags.has('all')) activeTags.delete('all');
        if (activeTags.has(target)) activeTags.delete(target);
        else activeTags.add(target);
        if (activeTags.size === 0) resetAll();
      }

      // Update items
      items.forEach(el => {
        if (el._ft) clearTimeout(el._ft);
        const next = itemMatches(el);
        const cur = el.getAttribute('data-filter-status');
        if (cur === 'active' && transitionDelay > 0) {
          el.setAttribute('data-filter-status','transition-out');
          el._ft = setTimeout(() => { setItemState(el, next); el._ft = null; }, transitionDelay);
        } else if (transitionDelay > 0) {
          el._ft = setTimeout(() => { setItemState(el, next); el._ft = null; }, transitionDelay);
        } else {
          setItemState(el, next);
        }
      });

      // Update buttons
      buttons.forEach(btn => {
        const t = (btn.getAttribute('data-filter-target') || '').trim().toLowerCase();
        let on = false;
        if (t === 'all') on = !hasRealActive();
        else if (t === 'reset') on = hasRealActive();
        else on = targetMatch === 'single' ? activeTags === t : activeTags.has(t);
        setButtonState(btn, on);
      });
    };

    group.addEventListener('click', e => {
      const btn = e.target.closest('[data-filter-target]');
      if (btn && group.contains(btn)) paint(btn.getAttribute('data-filter-target'));
    });

    paint('all');
  });
}

// Initialize Multi Filter Setup (Multi Match)
document.addEventListener('DOMContentLoaded', () => {
  initMutliFilterSetupMultiMatch();
});