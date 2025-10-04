"https://fonts.googleapis.com/icon?family=Material+Icons"

https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap

// Dark mode toggle
    const toggleBtn = document.querySelector('.toggle-btn');
    toggleBtn.addEventListener('click', () => {
      document.body.classList.toggle('dark');
    });

    <div class="toggle-btn">☾</div>

    .toggle-btn{
      cursor:pointer;
      font-size:20px;
      color:var(--light);
    }

    /* DARK MODE */
  body.dark{
      background:var(--dark);
      color:var(--light);
    }

  body.dark nav{
      background:var(--dark);
      border-color:#374151;
    }

  body.dark .nav-links a{
      color:var(--light);
    }

  body.dark .dropdown{
      background:var(--dark);
      border-color:#374151;
    }

  body.dark .dropdown a{
      color:var(--light);
    }

    body.dark .dropdown a:hover{
      background:#1f2937;
    }

    :root{
      --primary:#2563EB;
      --light:#ffffff;
      --dark:#111827;
      --gray:#6b7280;
      --navy:#0e2c6d;
    }

     /* garis slide indicator */
  .indicator{
      position:absolute;
      bottom:0;
      height:3px;
      background:var(--primary);
      border-radius:3px;
      transition:all 0.3s ease;
    }

    let lastScroll = 0;
    const bottomNav = document.getElementById("bottomNav");

    window.addEventListener("scroll", () => {
      let currentScroll = window.scrollY;

      if (currentScroll > lastScroll) {
        // Scroll ke bawah -> navbar sembunyi
        bottomNav.style.transform = "translateY(100%)";
      } else {
        // Scroll ke atas -> navbar muncul
        bottomNav.style.transform = "translateY(0)";
      }

      lastScroll = currentScroll;
    });
