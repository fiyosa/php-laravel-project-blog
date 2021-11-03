<nav>
  <div class="logo">
    <a class="{{ ($active == "home")?'nav-active':'' }}" href="/"><h4>FIYOSA</h4></a>
  </div>

  <ul class="nav-links">
    <li><a class="{{ ($active == "home")?'nav-active':'' }}" href="/">Home</a></li>
    <li><a class="{{ ($active == "blog")?'nav-active':'' }}" href="/blog">Blog</a></li>
    <li><a class="{{ ($active == "about")?'nav-active':'' }}" href="/about">About</a></li>
    <li><a class="{{ ($active == "login")?'nav-active':'' }}" href="/login">Login</a></li>
  </ul>

  <div class="burger">
    <div class="line1"></div>
    <div class="line2"></div>
    <div class="line3"></div>
  </div>
</nav>

<script>
  const navSlide = () => {
    const burger = document.querySelector('.burger');
    const nav= document.querySelector('.nav-links');
    const navLinks = document.querySelectorAll('.nav-links li');

    burger.addEventListener('click', ()=>{
      burger.classList.toggle('toggle');
      nav.classList.toggle('nav-active')
      navLinks.forEach((link, index) => {
        if(link.style.animation){
          link.style.animation = '';
        }
        else{
          link.style.animation = `navLinkFade 0.5S ease forwards ${index / 7 + 0.3}s`;
        }
      });
    });
  };

  navSlide();
</script>