// Custom GSAP Animation for Home Banner
document.addEventListener('DOMContentLoaded', function() {
    const tl = gsap.timeline();

    const moviaChars = document.querySelectorAll('#movia .character:not(.circle-char)');
    const mobaChars = document.querySelectorAll('#moba .character:not(.circle-char)');
    const circleA = document.getElementById('circleA');
    const circleB = document.getElementById('circleB');
    const connectingLine = document.getElementById('connectingLine');
    const arrow = document.getElementById('arrow');
    const mm_logo_animate = document.querySelector('.mm_logo_animate');

    gsap.set([moviaChars, mobaChars], { opacity: 0, x: 0 });
    gsap.set([circleA, circleB], { opacity: 1, x: 0 });
    gsap.set([connectingLine], { opacity: 1, scaleX: 0, Origin: "center center" });
    gsap.set(arrow, { opacity: 0, x: 0 });

    tl.to(moviaChars, {
        opacity: 1,
        x: 0,
        duration: 1,
        ease: "power2.out",
        stagger: 0.1,
        from: { x: 50 }
    })
    .to(mobaChars, {
        opacity: 1,
        x: 0,
        duration: 1,
        ease: "power2.out",
        stagger: 0.1,
        from: { x: -50 }
    }, "<");

    tl.to({}, { duration: 1 });

    tl.to(moviaChars, {
        opacity: 0,
        width: 0,
        marginRight: 0,
        duration: 1,
        stagger: { each: 0.1, from: "end" },
        ease: "power2.inOut"
    })
    .to(mobaChars, {
        opacity: 0,
        width: 0,
        marginRight: 0,
        duration: 1,
        stagger: { each: 0.1, from: "end" },
        ease: "power2.inOut"
    }, "<");

    tl.call(() => {
        const containerRect = mm_logo_animate.getBoundingClientRect();
        const circleARect = circleA.getBoundingClientRect();
        const circleBRect = circleB.getBoundingClientRect();

        const targetAX = -(circleARect.left - containerRect.left);
        const targetBX = containerRect.right - circleBRect.right;

        gsap.to(circleA, { x: targetAX, duration: 1, ease: "power2.out" });
        gsap.to(circleB, { x: targetBX, duration: 1, ease: "power2.out" });
    });

    tl.to(connectingLine, {
        scaleX: 1,
        duration: 1,
        ease: "power2.out"
    }, "+=0.3");

    tl.call(() => {
        const lineWidth = connectingLine.offsetWidth;

        const arrowLoop = gsap.timeline({ repeat: -1, repeatDelay: 1 });
        arrowLoop
        .set(arrow, { x: 0, opacity: 1 })
        .to(arrow, {
            x: lineWidth,
            duration: 2,
            ease: "power2.inOut"
        })
        .to(arrow, {
            opacity: 0,
            duration: 0.5,
            ease: "power2.out"
        });
    });

    const addHoverEffects = () => {
        [circleA, circleB].forEach(circle => {
            circle.addEventListener('mouseenter', () => {
                gsap.to(circle, { scale: 1.1, duration: 0.3, ease: "power2.out" });
            });
            circle.addEventListener('mouseleave', () => {
                gsap.to(circle, { scale: 1, duration: 0.3, ease: "power2.out" });
            });
        });
    };
    tl.call(addHoverEffects, null, "+=1");
});


// Filter Toggle
jQuery(function($) {
  const $toggleBtn = $('.btn_toggle_filter');
  const $filterWrap = $('.div_toggle_filter');
  const $filters = $('.div_filtres');

  let filterHeight = $filters.outerHeight();

  $toggleBtn.on('click', function() {

    $filterWrap.toggleClass('filter_closed');

    if ($filterWrap.hasClass('filter_closed')) {
      $filters.css('height', filterHeight); 
      $filters[0].offsetHeight;
      $filters.addClass('is_closed').css('height', 0);
    } else {
      $filters.removeClass('is_closed').css('height', filterHeight);
      setTimeout(() => {
        $filters.css('height', 'auto');
      }, 400);
    }
  });
});
document.addEventListener('DOMContentLoaded', () => {
  if (!window.gsap || !window.ScrollTrigger) return;
  gsap.registerPlugin(ScrollTrigger);

  const section = document.querySelector('.sec_nos_serv_home');
  if (!section) return;

  const bg    = section.querySelector('.div_nos_serv_home .bg');
  const title = section.querySelector('.nos_serv_title');
  const desc  = section.querySelector('.mid_nos_serv_home > p');
  if (!bg) return;

  // Initial states
  if (title) gsap.set(title, { '--clipRight': '100%' });
  if (desc)  gsap.set(desc,  { autoAlpha: 0, y: 12 });

  // Reveal timeline (plays once)
  const revealTl = gsap.timeline({ paused: true });
  if (title) revealTl.to(title, { '--clipRight': '0%', duration: 0.4, ease: 'power2.out' }, 0);
  if (desc)  revealTl.to(desc,  { autoAlpha: 1, y: 0, duration: 0.5, ease: 'power2.out' }, 0.1);

  let revealed = false;

  const tlBg = gsap.timeline({
    scrollTrigger: {
      trigger: section,
      start: 'top 75%',
      end: 'top 20%',
      invalidateOnRefresh: true,
      onUpdate(self) {
        if (!revealed && self.progress >= 0.999) {
          revealTl.play(0);
          revealed = true; // lock it; don’t reverse on scroll up
        }
      }
    }
  });

  tlBg.fromTo(bg, { scaleX: 0.78 }, { scaleX: 1, ease: 'none' });

  // If the page loads already past the end, force the reveal
  const st = tlBg.scrollTrigger;
  if (st && st.progress >= 0.999) {
    revealTl.progress(1);
    revealed = true;
  }
});


function initDirectionalButtonHover() {
  // Button hover animation
  document.querySelectorAll('[data-btn-hover]').forEach(button => {
    button.addEventListener('mouseenter', handleHover);
    button.addEventListener('mouseleave', handleHover);
  });

  function handleHover(event) {
    const button = event.currentTarget;
    const buttonRect = button.getBoundingClientRect();

    // Get the button's dimensions and center
    const buttonWidth = buttonRect.width;
    const buttonHeight = buttonRect.height;
    const buttonCenterX = buttonRect.left + buttonWidth / 2;
    const buttonCenterY = buttonRect.top + buttonHeight / 2;

    // Calculate mouse position
    const mouseX = event.clientX;
    const mouseY = event.clientY;

    // Offset from the top-left corner in percentage
    const offsetXFromLeft = ((mouseX - buttonRect.left) / buttonWidth) * 100;
    const offsetYFromTop = ((mouseY - buttonRect.top) / buttonHeight) * 100;

    // Offset from the center in percentage
    let offsetXFromCenter = ((mouseX - buttonCenterX) / (buttonWidth / 2)) * 50;

    // Convert to absolute values
    offsetXFromCenter = Math.abs(offsetXFromCenter);

    // Update position and size of .btn__circle
    const circle = button.querySelector('.btn__circle');
    if (circle) {
      circle.style.left = `${offsetXFromLeft.toFixed(1)}%`;
      circle.style.top = `${offsetYFromTop.toFixed(1)}%`;
      circle.style.width = `${115 + offsetXFromCenter.toFixed(1) * 2}%`;
    }
  }
}

// Initialize Directional Button Hover
document.addEventListener('DOMContentLoaded', function() {
  initDirectionalButtonHover();
});

document.addEventListener('DOMContentLoaded', () => {
  if (!window.gsap || !window.ScrollTrigger) {
    console.warn('GSAP or ScrollTrigger missing');
    return;
  }
  gsap.registerPlugin(ScrollTrigger);

  const el = document.querySelector('.sec_nos_serv_home .desc-text');
  if (!el) return;

  // Ensure starting position
  gsap.set(el, { yPercent: 110 });

  // Slide up from the bottom edge of the reveal box
  gsap.to(el, {
    yPercent: 0,
    duration: 0.9,
    ease: 'power3.out',
    scrollTrigger: {
      trigger: '.sec_nos_serv_home .mid_nos_serv_home',
      start: 'top 75%',
      // set to true if you never want it to reverse
      once: true
      // if you DO want it to reverse when scrolling back up, remove "once"
      // toggleActions: 'play none none reverse'
    }
  });
});

gsap.registerPlugin(ScrollTrigger, Flip);

(function initHeroFlip() {
  const wrappers = document.querySelectorAll("[data-flip-element='wrapper']");
  const target = document.querySelector("[data-flip-element='target']");
  if (!wrappers.length || !target) return;

  let tl;

  function build() {
    if (tl) {
      tl.kill();
      gsap.set(target, { clearProps: "all" });
    }

    tl = gsap.timeline({
      scrollTrigger: {
        trigger: wrappers[0],                           // start at BIG
        start: "center center",
        endTrigger: wrappers[wrappers.length - 1],      // end at SMALL
        end: "center center",
        scrub: 0.25
      }
    });

    wrappers.forEach((el, i) => {
      const j = i + 1;
      if (j < wrappers.length) {
        const next = wrappers[j];
        const nextRect = next.getBoundingClientRect();
        const thisRect = el.getBoundingClientRect();
        const nextDistance = nextRect.top + window.pageYOffset + next.offsetHeight / 1.5;
        const thisDistance = thisRect.top + window.pageYOffset + el.offsetHeight /1.5;
        const offset = nextDistance - thisDistance;

        tl.add(Flip.fit(target, next, { duration: offset, ease: "none" }));
      }
    });
  }

  build();

  let resizeTimer;
  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(build, 100);
  });
})();
