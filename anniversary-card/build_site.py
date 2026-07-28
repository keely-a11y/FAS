#!/usr/bin/env python3
"""Build the animated Gilded Orbit anniversary site with embedded fonts/photo."""
import json, base64, os, sys

D = os.path.dirname(os.path.abspath(__file__))
fonts = json.load(open(f"{D}/fonts_b64.json"))

# optional real photograph
photo_b64 = None
for cand in [f"{D}/couple-photo.jpg", f"{D}/couple-photo.jpeg", f"{D}/couple-photo.png"]:
    if os.path.exists(cand):
        photo_b64 = base64.b64encode(open(cand, "rb").read()).decode()
        photo_mime = "image/png" if cand.endswith("png") else "image/jpeg"
        break

if photo_b64:
    photo_block = f'''<figure class="photo-frame reveal">
        <div class="frame-outer"><div class="frame-inner">
          <img src="data:{photo_mime};base64,{photo_b64}" alt="Rob and Rae Lee together on a rooftop in Barcelona, the city skyline behind them">
        </div></div>
        <figcaption class="mono-label">ROB&nbsp;&amp;&nbsp;RAE&nbsp;LEE&nbsp;&nbsp;&middot;&nbsp;&nbsp;EST.&nbsp;2002</figcaption>
      </figure>'''
else:
    photo_block = '''<figure class="photo-frame reveal">
        <div class="frame-outer"><div class="frame-inner placeholder">
          <svg viewBox="0 0 200 140" aria-hidden="true">
            <circle cx="86" cy="70" r="34" fill="none" stroke="#c9a24b" stroke-width="1.4"/>
            <circle cx="114" cy="70" r="34" fill="none" stroke="#e6c87f" stroke-width="1.4"/>
          </svg>
        </div></div>
        <figcaption class="mono-label">ROB&nbsp;&amp;&nbsp;RAE&nbsp;LEE&nbsp;&nbsp;&middot;&nbsp;&nbsp;EST.&nbsp;2002</figcaption>
      </figure>'''

html = r'''<title>Rob & Rae Lee — Twenty-Four Years</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
@font-face{font-family:'Italiana';src:url(data:font/woff2;base64,__ITALIANA__) format('woff2');font-display:block}
@font-face{font-family:'Crimson Pro';font-style:italic;src:url(data:font/woff2;base64,__CRIMSON_I__) format('woff2');font-display:block}
@font-face{font-family:'Crimson Pro';font-style:normal;src:url(data:font/woff2;base64,__CRIMSON__) format('woff2');font-display:block}
@font-face{font-family:'Geist Mono';src:url(data:font/woff2;base64,__MONO__) format('woff2');font-display:block}
@font-face{font-family:'Nothing You Could Do';src:url(data:font/woff2;base64,__SCRIPT__) format('woff2');font-display:block}

:root{
  --ink:#0d1b24; --ink-2:#122431; --gold:#c9a24b; --gold-pale:#e6c87f;
  --cream:#f4eee1; --cream-dim:#ece4d2; --cream-ink:#1d2b33; --gold-deep:#7a6230;
}
*{margin:0;padding:0;box-sizing:border-box}
html{scroll-behavior:smooth}
body{background:var(--ink);color:var(--cream);font-family:'Crimson Pro',Georgia,serif;
  overflow-x:hidden;-webkit-font-smoothing:antialiased}

.mono-label{font-family:'Geist Mono',monospace;font-size:.72rem;letter-spacing:.42em;
  text-indent:.42em;color:var(--gold-pale);text-transform:uppercase}

/* ---------------- hero ---------------- */
.hero{position:relative;height:100vh;height:100svh;min-height:640px;overflow:hidden}
#sky{position:absolute;inset:0;width:100%;height:100%;display:block}
.hero-top,.hero-bottom{position:absolute;left:0;right:0;text-align:center;z-index:2}
.hero-top{top:clamp(28px,5.5vh,60px)}
.hero-bottom{bottom:clamp(26px,6vh,72px);display:flex;flex-direction:column;align-items:center;gap:1.1rem}
.names{font-family:'Italiana',serif;font-weight:400;color:var(--cream);
  font-size:clamp(2.6rem,7.5vw,5.2rem);line-height:1.05;letter-spacing:.015em;text-wrap:balance}
.names .amp{font-family:'Crimson Pro',serif;font-style:italic;color:var(--gold-pale);
  font-size:.72em;padding:0 .12em}
.rule{display:flex;align-items:center;gap:14px;color:var(--gold)}
.rule .line{width:110px;height:1px;background:currentColor;opacity:.55}
.rule .dia{width:8px;height:8px;border:1px solid currentColor;transform:rotate(45deg);opacity:.85}
.years{font-family:'Geist Mono',monospace;font-size:.8rem;letter-spacing:.5em;text-indent:.5em;color:var(--gold)}
.fade{opacity:0;transform:translateY(14px);animation:rise 1.6s cubic-bezier(.2,.6,.2,1) both}
.d1{animation-delay:.4s}.d2{animation-delay:4.9s}.d3{animation-delay:5.5s}.d4{animation-delay:5.9s}
@keyframes rise{to{opacity:1;transform:none}}
.scroll-cue{position:absolute;bottom:0;left:50%;transform:translateX(-50%);width:1px;height:0;
  background:linear-gradient(to bottom,transparent,var(--gold));z-index:2;
  animation:cue 2.2s ease 6.8s both}
@keyframes cue{to{height:clamp(10px,2.5vh,26px)}}

/* ---------------- shared section shell ---------------- */
section{position:relative}
.shell{max-width:920px;margin:0 auto;padding:clamp(5rem,12vh,9rem) clamp(1.4rem,6vw,3rem);text-align:center}

/* ---------------- photo ---------------- */
.photo-sec{background:linear-gradient(var(--ink),var(--ink-2) 55%,var(--ink))}
.photo-frame{display:inline-block}
.frame-outer{border:1px solid var(--gold);padding:clamp(10px,1.6vw,16px);position:relative}
.frame-outer::before,.frame-outer::after{content:"";position:absolute;width:26px;height:26px;pointer-events:none}
.frame-outer::before{top:-7px;left:-7px;border-top:1px solid var(--gold-pale);border-left:1px solid var(--gold-pale)}
.frame-outer::after{bottom:-7px;right:-7px;border-bottom:1px solid var(--gold-pale);border-right:1px solid var(--gold-pale)}
.frame-inner{border:1px solid rgba(201,162,75,.45);padding:clamp(8px,1.2vw,12px);background:var(--ink)}
.frame-inner img{display:block;max-width:min(640px,78vw);width:100%;height:auto;
  filter:saturate(.92) contrast(1.02)}
.frame-inner.placeholder{width:min(640px,78vw);aspect-ratio:10/7;display:grid;place-items:center}
.frame-inner.placeholder svg{width:52%;opacity:.8}
.photo-sec figcaption{margin-top:2rem}
.photo-lede{margin-top:3.2rem;font-style:italic;font-size:clamp(1.15rem,2.2vw,1.45rem);
  color:var(--cream-dim);max-width:34ch;margin-left:auto;margin-right:auto;line-height:1.75;text-wrap:balance}

/* ---------------- letter ---------------- */
.letter{background:var(--cream);color:var(--cream-ink)}
.letter .shell{max-width:760px}
.ringmark{display:inline-block;margin-bottom:.9rem}
.ringmark svg{width:92px;height:auto;display:block}
.letter .mono-label{color:var(--gold-deep)}
.salutation{font-family:'Italiana',serif;font-size:clamp(2rem,5vw,3rem);margin:2.6rem 0 2.8rem;color:var(--cream-ink)}
.letter p{font-style:italic;font-size:clamp(1.18rem,2.3vw,1.5rem);line-height:1.8;
  max-width:46ch;margin:0 auto 1.9rem;text-wrap:pretty}
.letter p:last-of-type{margin-bottom:0}

/* ---------------- closing ---------------- */
.closing{background:var(--ink)}
#orbit2{display:block;margin:0 auto 1.4rem;width:min(320px,70vw);height:auto}
.big{font-family:'Italiana',serif;font-weight:400;font-size:clamp(2.2rem,6vw,4rem);
  color:var(--cream);margin:1.2rem 0 2.6rem;text-wrap:balance}
.withlove{font-style:italic;font-size:clamp(1.15rem,2.2vw,1.4rem);color:var(--cream-dim)}
.sig{font-family:'Nothing You Could Do',cursive;font-size:clamp(2rem,5vw,2.9rem);
  color:var(--gold-pale);margin:1.6rem 0 1.4rem}
.dia-end-wrap{display:inline-block;margin-top:3rem}
.closing .dia-end{width:9px;height:9px;border:1px solid var(--gold);transform:rotate(45deg);
  display:inline-block}

/* ---------------- reveals ---------------- */
.reveal{opacity:0;transform:translateY(26px);
  transition:opacity 1.1s cubic-bezier(.2,.6,.2,1),transform 1.1s cubic-bezier(.2,.6,.2,1);
  transition-delay:var(--d,0s)}
.reveal.on{opacity:1;transform:none}

@media (prefers-reduced-motion: reduce){
  .fade,.scroll-cue{animation:none;opacity:1;transform:none;height:26px}
  .reveal{opacity:1;transform:none;transition:none}
  html{scroll-behavior:auto}
}
</style>

<main>
  <section class="hero" aria-label="Twenty-four years — Rob and Rae Lee">
    <canvas id="sky" aria-hidden="true"></canvas>
    <div class="hero-top">
      <div class="mono-label fade d1">Twenty-Four&nbsp;Years</div>
    </div>
    <div class="hero-bottom">
      <h1 class="names fade d2">Rob<span class="amp">&amp;</span>Rae&nbsp;Lee</h1>
      <div class="rule fade d3" aria-hidden="true"><span class="line"></span><span class="dia"></span><span class="line"></span></div>
      <div class="years fade d4">2002&nbsp;&middot;&nbsp;2026</div>
    </div>
    <div class="scroll-cue" aria-hidden="true"></div>
  </section>

  <section class="photo-sec">
    <div class="shell">
      __PHOTO__
      <p class="photo-lede reveal" style="--d:.25s">Twenty-four years in,
        and still a great team&nbsp;&mdash; wherever you&nbsp;go.</p>
    </div>
  </section>

  <section class="letter">
    <div class="shell">
      <span class="ringmark reveal" aria-hidden="true">
        <svg viewBox="0 0 120 74"><circle cx="49" cy="37" r="26" fill="none" stroke="#c9a24b" stroke-width="1.6"/><circle cx="71" cy="37" r="26" fill="none" stroke="#7a6230" stroke-width="1.6"/></svg>
      </span>
      <div class="mono-label reveal" style="--d:.1s">XXIV</div>
      <h2 class="salutation reveal" style="--d:.2s">Rob &amp; Rae&nbsp;Lee,</h2>
      <p class="reveal" style="--d:.3s">Twenty-four years of marriage is a real accomplishment,
        and you've made every one of them&nbsp;count.</p>
      <p class="reveal" style="--d:.4s">You two make a great team. You work hard, you look out
        for each other, and you've built a life and a family to be proud&nbsp;of.</p>
      <p class="reveal" style="--d:.5s">We're very proud of you both,
        and we love you very&nbsp;much.</p>
    </div>
  </section>

  <section class="closing">
    <div class="shell">
      <canvas id="orbit2" width="640" height="220" aria-hidden="true"></canvas>
      <h2 class="big reveal">Happy 24<span style="font-size:.6em;vertical-align:.5em">th</span> Anniversary</h2>
      <div class="withlove reveal" style="--d:.15s">With all our love,</div>
      <div class="sig reveal" style="--d:.3s">Mom &amp; Dad</div>
      <div class="mono-label reveal" style="--d:.45s">Bob&nbsp;&amp;&nbsp;Mary&nbsp;Fay</div><br>
      <span class="dia-end-wrap reveal" style="--d:.6s" aria-hidden="true"><span class="dia-end"></span></span>
    </div>
  </section>
</main>

<script>
(function(){
  const reduce = matchMedia('(prefers-reduced-motion: reduce)').matches;

  /* ---------- hero sky ---------- */
  const cv = document.getElementById('sky'), cx2 = cv.getContext('2d');
  const GOLD='201,162,75', PALE='230,200,127', CREAMC='255,246,224';
  const N=24; let W,H,CX,CY,R0,R1,radii=[],rings=[],stars=[],dpr=1;

  function mulberry(seed){return function(){seed|=0;seed=seed+0x6D2B79F5|0;
    let t=Math.imul(seed^seed>>>15,1|seed);t=t+Math.imul(t^t>>>7,61|t)^t;
    return((t^t>>>14)>>>0)/4294967296}}

  function layout(){
    dpr=Math.min(devicePixelRatio||1,2);
    W=cv.clientWidth;H=cv.clientHeight;
    cv.width=W*dpr;cv.height=H*dpr;cx2.setTransform(dpr,0,0,dpr,0,0);
    CX=W/2;CY=H*0.44;
    R1=Math.min(W*0.42,H*0.335);R0=R1/11.5;
    radii=[];for(let i=0;i<N;i++)radii.push(R0+(R1-R0)*i/(N-1));
    const rnd=mulberry(24);
    rings=radii.map((r,i)=>({r,
      p1:rnd()*6.283,p2:rnd()*6.283,p3:rnd()*6.283,
      amp:(0.9+rnd()*1.1)*(R1/560+0.6)*(0.25+0.75*r/R1),
      a:(i+1)%6===0?0.62:0.24+rnd()*0.16,
      w:(i+1)%6===0?1.5:0.8,
      pale:(i+1)%6===0}));
    const rs=mulberry(7);stars=[];
    for(let k=0;k<150;k++){
      const x=rs()*W,y=rs()*H*0.9;
      if(Math.hypot(x-CX,y-CY)<R1*0.32)continue;
      stars.push({x,y,r:0.4+rs()*1.1,ph:rs()*6.283,a:0.06+rs()*0.2});
    }
  }

  function wob(g,t){return (Math.sin(3*t+g.p1)+0.6*Math.sin(7*t+g.p2)+0.4*Math.sin(13*t+g.p3))*g.amp}

  function drawRing(g,frac){
    if(frac<=0)return;
    cx2.beginPath();
    const steps=Math.max(24,Math.floor(240*frac));
    for(let k=0;k<=steps;k++){
      const t=-Math.PI/2+6.28319*frac*k/steps;
      const r=g.r+wob(g,t);
      const x=CX+r*Math.cos(t),y=CY+r*Math.sin(t);
      k?cx2.lineTo(x,y):cx2.moveTo(x,y);
    }
    cx2.strokeStyle=`rgba(${g.pale?PALE:GOLD},${g.a})`;
    cx2.lineWidth=g.w;cx2.stroke();
  }

  const ease=x=>1-Math.pow(1-x,3);
  const t0=performance.now();

  function frame(now){
    const t=(now-t0)/1000;
    cx2.clearRect(0,0,W,H);
    /* soft center glow */
    const gl=cx2.createRadialGradient(CX,CY,0,CX,CY,R1*1.5);
    gl.addColorStop(0,'rgba(32,58,74,0.55)');gl.addColorStop(1,'rgba(32,58,74,0)');
    cx2.fillStyle=gl;cx2.fillRect(0,0,W,H);
    /* stars */
    for(const s of stars){
      const tw=reduce?1:(0.6+0.4*Math.sin(t*1.3+s.ph));
      cx2.beginPath();cx2.arc(s.x,s.y,s.r,0,6.2832);
      cx2.fillStyle=`rgba(${PALE},${s.a*tw})`;cx2.fill();
    }
    /* rings drawing in */
    let allDone=true;
    rings.forEach((g,i)=>{
      const frac=reduce?1:ease(Math.min(Math.max((t-0.12*i)/1.5,0),1));
      if(frac<1)allDone=false;
      drawRing(g,frac);
    });
    /* meridian + milestone ticks */
    const mA=reduce?1:Math.min(Math.max((t-3.4)/1.4,0),1);
    if(mA>0){
      cx2.strokeStyle=`rgba(${GOLD},${0.14*mA})`;cx2.lineWidth=0.8;
      cx2.beginPath();cx2.moveTo(CX,CY-R1-14);cx2.lineTo(CX,CY+R1+14);cx2.stroke();
      cx2.font=`${Math.max(10,R1*0.036)}px "Geist Mono",monospace`;
      cx2.textBaseline='middle';cx2.textAlign='left';
      rings.forEach((g,i)=>{
        const y=CY+g.r,mile=(i+1)%6===0,tk=mile?7:4;
        cx2.strokeStyle=`rgba(${PALE},${(mile?0.55:0.3)*mA})`;
        cx2.lineWidth=mile?1.4:0.8;
        cx2.beginPath();cx2.moveTo(CX-tk,y);cx2.lineTo(CX+tk,y);cx2.stroke();
        if(mile){
          const lbl=String(i+1).padStart(2,'0');
          cx2.fillStyle='#0d1b24';
          const wte=cx2.measureText(lbl).width;
          cx2.fillRect(CX+13,y-8,wte+8,16);
          cx2.fillStyle=`rgba(${PALE},${0.75*mA})`;
          cx2.fillText(lbl,CX+17,y+0.5);
        }
      });
    }
    /* the two lights on the shared outer ring */
    const lA=reduce?1:Math.min(Math.max((t-4.4)/1.6,0),1);
    if(lA>0){
      const g=rings[N-1];
      const base=-1.0123+(reduce?0:t*0.05);
      const sep=0.098;
      /* brightened passage */
      cx2.beginPath();
      for(let k=0;k<=60;k++){
        const a=base-0.30+0.60*k/60;
        const r=g.r+wob(g,a);
        const x=CX+r*Math.cos(a),y=CY+r*Math.sin(a);
        k?cx2.lineTo(x,y):cx2.moveTo(x,y);
      }
      cx2.strokeStyle=`rgba(${PALE},${0.8*lA})`;cx2.lineWidth=1.8;cx2.stroke();
      [[-sep/2,4.6],[sep/2,4.0]].forEach(([da,cr],i2)=>{
        const a=base+da,r=g.r+wob(g,a);
        const x=CX+r*Math.cos(a),y=CY+r*Math.sin(a);
        const pulse=reduce?1:(0.85+0.15*Math.sin(t*2.1+i2*2.4));
        const rg=cx2.createRadialGradient(x,y,0,x,y,26*pulse);
        rg.addColorStop(0,`rgba(${CREAMC},${0.85*lA})`);
        rg.addColorStop(0.28,`rgba(${PALE},${0.4*lA})`);
        rg.addColorStop(1,`rgba(${PALE},0)`);
        cx2.fillStyle=rg;cx2.beginPath();cx2.arc(x,y,26*pulse,0,6.2832);cx2.fill();
        cx2.fillStyle=`rgba(${CREAMC},${lA})`;
        cx2.beginPath();cx2.arc(x,y,cr,0,6.2832);cx2.fill();
      });
    }
    if(!reduce||!frame.once){frame.once=true;
      if(!reduce)requestAnimationFrame(frame);}
  }

  layout();addEventListener('resize',()=>{layout();if(reduce)frame(performance.now())});
  requestAnimationFrame(frame);

  /* ---------- closing mini orbit ---------- */
  const c2=document.getElementById('orbit2'),ctx2=c2.getContext('2d');
  function orbit2(now){
    const t=now/1000,w=640,h=220,cx=w/2,cy=h*0.52;
    ctx2.clearRect(0,0,w,h);
    ctx2.strokeStyle='rgba(230,200,127,0.75)';ctx2.lineWidth=1.4;
    ctx2.beginPath();ctx2.ellipse(cx,cy,250,66,0,0,6.2832);ctx2.stroke();
    ctx2.strokeStyle='rgba(201,162,75,0.35)';ctx2.lineWidth=1;
    ctx2.beginPath();ctx2.ellipse(cx,cy,196,50,0,0,6.2832);ctx2.stroke();
    const base=reduce?-0.9:t*0.35,sep=0.16;
    [[-sep/2,4.4],[sep/2,3.8]].forEach(([d,cr],i)=>{
      const a=base+d,x=cx+250*Math.cos(a),y=cy+66*Math.sin(a);
      const rg=ctx2.createRadialGradient(x,y,0,x,y,20);
      rg.addColorStop(0,'rgba(255,246,224,0.9)');rg.addColorStop(0.3,'rgba(230,200,127,0.4)');
      rg.addColorStop(1,'rgba(230,200,127,0)');
      ctx2.fillStyle=rg;ctx2.beginPath();ctx2.arc(x,y,20,0,6.2832);ctx2.fill();
      ctx2.fillStyle='rgba(255,246,224,1)';
      ctx2.beginPath();ctx2.arc(x,y,cr,0,6.2832);ctx2.fill();
    });
    if(!reduce)requestAnimationFrame(orbit2);
  }
  requestAnimationFrame(orbit2);

  /* ---------- scroll reveals ---------- */
  const io=new IntersectionObserver(es=>es.forEach(e=>{
    if(e.isIntersecting){e.target.classList.add('on');io.unobserve(e.target)}
  }),{threshold:0.18});
  document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
})();
</script>
'''

html = html.replace("__ITALIANA__", fonts["italiana"]) \
           .replace("__CRIMSON_I__", fonts["crimson_i"]) \
           .replace("__CRIMSON__", fonts["crimson"]) \
           .replace("__MONO__", fonts["mono"]) \
           .replace("__SCRIPT__", fonts["script"]) \
           .replace("__PHOTO__", photo_block)

out = f"{D}/rob-raelee-anniversary.html"
open(out, "w").write(html)
print("wrote", out, len(html)//1024, "KB", "photo:", bool(photo_b64))
