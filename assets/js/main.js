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
    gsap.set([connectingLine], { opacity: 1, scaleX: 0, transformOrigin: "center center" });
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
