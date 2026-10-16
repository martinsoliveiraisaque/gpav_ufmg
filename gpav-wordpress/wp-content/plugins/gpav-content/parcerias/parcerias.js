/* ==========================================================================
   parcerias.js — comportamento da página "Parcerias"
   Blocos: 1) jogo Fato ou Fake  2) números e gráfico animados
           3) galeria (filtro e foto ampliada)  4) planos e simulador
   Tudo roda só dentro do elemento ".pc"; se a página não existir, nada acontece.
   ========================================================================== */
(() => {
  const root = document.querySelector('.pc');
  if (!root) return;
  const $  = s => root.querySelector(s);              // pega um elemento da página
  const $$ = s => [...root.querySelectorAll(s)];      // pega vários
  const RM = matchMedia('(prefers-reduced-motion: reduce)').matches;   // usuário prefere menos animação?

  /* ---------- 1) JOGO FATO OU FAKE ----------
     Q = perguntas. Formato: [texto, 1 se for FATO ou 0 se for FAKE, explicação mostrada depois].
     Para incluir uma pergunta, acrescente mais uma linha de colchetes. */
  const Q = [
    ["O Aedes aegypti transmite dengue, zika e chikungunya.", 1, "O mesmo mosquito transmite as três doenças."],
    ["A vacina da gripe também protege contra a dengue.", 0, "São vírus diferentes. Cada um precisa da sua própria vacina."],
    ["Zika durante a gravidez pode afetar o desenvolvimento do bebê.", 1, "A infecção na gestação está associada à microcefalia e a outras alterações."],
    ["Repelente caseiro de cravo com álcool substitui o repelente registrado.", 0, "Misturas caseiras duram pouco e não têm comprovação. Prefira produtos registrados na Anvisa."]
  ];
  let qi = 0, pts = 0, done = false;                  // qi = pergunta atual, pts = acertos, done = round terminado
  const btnF = $('#pc-gf'), btnK = $('#pc-gk'), btnN = $('#pc-gn');
  const lock = v => { btnF.disabled = btnK.disabled = v; };

  function show() {                                   // mostra a pergunta atual
    $('#pc-gcount').textContent = `Pergunta ${qi + 1} de ${Q.length}`;
    $('#pc-gq').textContent = Q[qi][0];
    $('#pc-gfb').innerHTML = '';
    btnN.style.display = 'none';
    lock(false);
    $('#pc-gbar').style.width = (qi / Q.length * 100) + '%';
  }
  function answer(v) {                                // v = 1 (clicou Fato) ou 0 (clicou Fake)
    const q = Q[qi], ok = v === q[1];
    if (ok) pts++;
    lock(true);
    $('#pc-gfb').innerHTML = `<b class="${ok ? 'ok' : 'no'}">${ok ? 'Acertou!' : 'Era ' + (q[1] ? 'fato' : 'fake') + '.'}</b>${q[2]}`;
    btnN.style.display = 'inline-block';
    btnN.textContent = qi === Q.length - 1 ? 'Ver resultado' : 'Próxima';
    $('#pc-gbar').style.width = ((qi + 1) / Q.length * 100) + '%';
  }
  btnF.onclick = () => answer(1);
  btnK.onclick = () => answer(0);
  btnN.onclick = () => {                              // botão "Próxima" / "Ver resultado" / "Jogar de novo"
    if (done) { qi = 0; pts = 0; done = false; show(); return; }
    if (qi < Q.length - 1) { qi++; show(); return; }
    done = true;
    $('#pc-gcount').textContent = 'Fim do round';
    $('#pc-gq').textContent = `Você acertou ${pts} de ${Q.length}. Nos eventos do GPAv tem muito mais.`;
    $('#pc-gfb').innerHTML = '';
    lock(true);
    btnN.textContent = 'Jogar de novo';
  };
  show();

  /* ---------- 2) NÚMEROS E GRÁFICO ANIMADOS ----------
     Um observador espera o elemento aparecer na tela: nos números (data-n) roda a contagem;
     no gráfico adiciona a classe "on", que desenha a linha. */
  const fmt = (p, x) => (p + Math.round(x)).replace(/\B(?=(\d{3})+$)/g, '.');
  const io = new IntersectionObserver(entries => entries.forEach(e => {
    if (!e.isIntersecting) return;
    io.unobserve(e.target);
    const el = e.target;
    if (!el.dataset.n) { el.classList.add('on'); return; }          // é o gráfico
    const total = +el.dataset.n, p = el.dataset.p || '';
    if (RM) { el.textContent = fmt(p, total); return; }
    let start = null;
    const step = t => {
      start ??= t;
      const k = Math.min((t - start) / 1400, 1);
      el.textContent = fmt(p, total * (1 - Math.pow(1 - k, 3)));
      if (k < 1) requestAnimationFrame(step);
    };
    requestAnimationFrame(step);
  }), { threshold: .4 });
  $$('[data-n]').forEach(n => io.observe(n));
  io.observe($('#pc-chart'));

  /* ---------- 3) GALERIA ----------
     Filtro: mostra só as fotos cujo data-t é igual ao data-f do botão ("all" mostra todas). */
  $$('.pc-chips button').forEach(b => b.onclick = () => {
    $$('.pc-chips button').forEach(x => x.setAttribute('aria-pressed', x === b));
    $$('.pc-fig').forEach(f => f.hidden = b.dataset.f !== 'all' && f.dataset.t !== b.dataset.f);
  });
  // Foto ampliada: copia a imagem clicada para a janela (dialog). Fecha pelo botão ou clicando fora.
  const lb = $('#pc-lb');
  $$('.pc-fig button').forEach(b => b.onclick = () => {
    const i = b.querySelector('img');
    lb.querySelector('img').src = i.src;
    lb.querySelector('img').alt = i.alt;
    lb.showModal();
  });
  $('#pc-lbx').onclick = () => lb.close();
  lb.onclick = e => { if (e.target === lb) lb.close(); };

  /* ---------- 4) PLANOS E SIMULADOR ----------
     R = nomes dos benefícios (na ordem das linhas dos cartões).
     T = planos, na ordem em que aparecem: k = nome, m = medalha, c = classe de cor,
         p = valor mensal em reais, v = valor de cada benefício (mesma ordem de R; "–" = não inclui). */
  const MAIL = 'contatogpav.ufmg@gmail.com';          // e-mail que recebe as propostas
  const WA = '5531999928910';                         // WhatsApp (só números, com 55 e DDD)
  const R = ["Logo em publicações", "Posts institucionais", "Stories com agradecimento", "Banner em eventos", "Brindes da empresa", "Logo em minicursos", "Logo em cartilhas"];
  const T = [
    { k: 'Ouro',   m: '🥇', c: 'ouro',   p: 2000, v: ['✓ destaque', '12', '✓', '✓', '✓', '✓', 'Personalizado'] },
    { k: 'Prata',  m: '🥈', c: 'prata',  p: 1000, v: ['✓', '6', '✓', '✓', '✓', '✓', '✓'] },
    { k: 'Bronze', m: '🥉', c: 'bronze', p: 500,  v: ['✓', '2', '–', '–', '✓', '–', '✓'] }
  ];
  let sel = 0;                                        // plano escolhido (0 = Ouro, o primeiro)
  const brl = n => n.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL', maximumFractionDigits: 0 });

  // Monta os cartões dos planos a partir de T e R.
  $('#pc-tiers').innerHTML = T.map((t, i) =>
    `<label class="pc-tier ${t.c}"><input type="radio" name="pc-tier" value="${i}"><header><h3><span aria-hidden="true">${t.m}</span> ${t.k}</h3><span>${brl(t.p)}/mês</span></header><ul>` +
    t.v.map((v, j) => `<li class="${v === '–' ? 'off' : ''}"><span>${R[j]}</span><b>${v}</b></li>`).join('') +
    `</ul></label>`).join('');

  // Atualiza o simulador sempre que algo muda (plano, meses ou nome da empresa).
  function update() {
    const t = T[sel], m = +$('#pc-mes').value, nome = $('#pc-emp').value.trim();
    $$('.pc-tier').forEach((x, i) => x.classList.toggle('sel', i === sel));
    $$('.pc-tier input')[sel].checked = true;
    $('#pc-mv').textContent = m;
    $('#pc-ts').textContent = `Plano ${t.k} · ${brl(t.p)}/mês · ${m} meses`;
    $('#pc-tt').textContent = brl(t.p * m);
    // Mensagem de interesse + links (Gmail, e-mail padrão do aparelho e WhatsApp), já com assunto e texto.
    const msg = `Olá! ${nome ? 'Somos a ' + nome + ' e t' : 'T'}emos interesse no plano ${t.k} (${m} meses) de parceria com o GPAv.`;
    const en = encodeURIComponent, su = en('Parceria GPAv — plano ' + t.k);
    $('#pc-bm').href = `https://mail.google.com/mail/?view=cm&fs=1&to=${MAIL}&su=${su}&body=${en(msg)}`;
    $('#pc-bd').href = `mailto:${MAIL}?subject=${su}&body=${en(msg)}`;
    $('#pc-bw').href = `https://wa.me/${WA}?text=${en(msg)}`;
  }
  $$('.pc-tier input').forEach((r, i) => r.onchange = () => { sel = i; update(); });
  $('#pc-mes').oninput = update;
  $('#pc-emp').oninput = update;
  update();                                           // cálculo inicial
})();
