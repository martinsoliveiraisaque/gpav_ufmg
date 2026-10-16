---
layout: default
title: Parcerias
lang_root: pt
lang_tag: pt-BR
lang_switch: /en/
lang_switch_label: EN
permalink: /pt/parcerias/
---
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600;700&display=swap">
<link rel="stylesheet" href="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/parcerias.css">
<!--
PÁGINA PARCERIAS (fragmento de HTML, sem cabeçalho nem rodapé: o tema do site já os fornece).
{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/img é trocado automaticamente pelo endereço da pasta de imagens (parcerias/img).
Para editar TEXTOS: altere aqui. Para editar PLANOS, VALORES e PERGUNTAS DO JOGO: veja parcerias.js.
Para mudar cores e formatos: veja parcerias.css.
-->
<div class="pc">
<!-- ABERTURA: título, texto e botões à esquerda; jogo Fato ou Fake à direita (a lógica do jogo está em parcerias.js) -->
<header class="pc-top">
<div>
<h1>Ciência para além da universidade.</h1>
<p class="pc-lead">O Nosso Laboratório de Pesquisa e Extensão Científica convida a sua empresa a unir forças em uma parceria estratégica focada no impacto social, inovação e disseminação do conhecimento. Como grupo dedicado à divulgação científica, atuamos como uma ponte direta entre o ambiente de pesquisa e a comunidade, aproximando a ciência da sociedade de forma acessível e atrativa.</p>
<div class="pc-actions">
<a class="pc-btn main" href="#pc-planos">Montar minha parceria</a>
<a class="pc-btn" href="#pc-eventos">Ver o GPAv em campo</a>
</div>
</div>
<div class="pc-game" aria-live="polite">
<small id="pc-gcount">Fato ou Fake — jogue um round</small>
<div class="pc-q" id="pc-gq"></div>
<div class="pc-ans"><button type="button" id="pc-gf">Fato</button><button type="button" id="pc-gk">Fake</button></div>
<div class="pc-fb" id="pc-gfb"></div>
<button type="button" class="pc-btn main pc-next" id="pc-gn">Próxima</button>
<div class="pc-bar"><i id="pc-gbar"></i></div>
</div>
</header>
<!-- QUEM SOMOS: título e texto ao lado da foto da equipe, e três diferenciais embaixo -->
<section id="pc-quem-somos">
<div class="pc-about">
<div>
<h2>Referência em viroses de importância para a saúde pública</h2>
<p>O GPAv, sediado no ICB/UFMG, coordenado pela Profa. Dra. Vivian Vasconcelos Costa e apoiado pelo INCT Dengue e CT Terapias, dedica-se ao estudo de viroses críticas para a saúde pública — Dengue, Zika, Chikungunya, Covid, Influenza, etc.</p>
<p>Nossa missão é transformar ciência básica em retornos para a sociedade, investigando mecanismos imunológicos e buscando novos tratamentos.</p>
</div>
<figure><img src="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/img/equipe.jpg" alt="Equipe do GPAv no ICB/UFMG" loading="lazy"><figcaption>Equipe GPAv, ICB/UFMG</figcaption></figure>
</div>
<div class="pc-cols">
<div><h3>Trajetória internacional</h3><p>Liderança com sólida trajetória internacional e infraestrutura laboratorial de ponta na UFMG.</p></div>
<div><h3>Do laboratório à população</h3><p>Conectamos o rigor acadêmico a aplicações práticas que salvam vidas e reduzem a sobrecarga no sistema de saúde.</p></div>
<div><h3>Resultados promissores</h3><p>Pesquisa voltada para resultados com impacto científico, social e econômico.</p></div>
</div>
</section>
<!-- ALCANCE: bloco verde com números animados (data-n = valor final, data-p = sinal) e gráfico do Instagram (desenho SVG) -->
<section id="pc-alcance">
<div class="pc-reach">
<div>
<h2>Alcance medido em campo e nas redes</h2>
<div class="pc-stats">
<div><b data-n="2000" data-p="+">0</b><span>participantes presenciais</span></div>
<div><b data-n="800" data-p="+">0</b><span>materiais educativos</span></div>
<div><b data-n="30" data-p="+">0</b><span>escolas</span></div>
<div><b data-n="3">0</b><span>universidades</span></div>
</div>
</div>
<div class="pc-chart" id="pc-chart">
<div class="big">444,5 mil<em>↑ 100%</em></div>
<p>visualizações no Instagram</p>
<svg viewBox="0 0 560 150" preserveAspectRatio="none" role="img" aria-label="Gráfico de visualizações ao longo do tempo">
<defs><linearGradient id="pc-af" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#BFE3CE" stop-opacity=".35"/><stop offset="1" stop-color="#BFE3CE" stop-opacity="0"/></linearGradient></defs>
<path d="M0,118 L60,118 L90,110 L110,60 L130,90 L150,78 L170,95 L190,82 L210,100 L230,88 L250,98 L270,90 L290,100 L320,92 L340,102 L360,96 L380,60 L400,92 L420,72 L440,98 L470,104 L500,108 L530,110 L560,112 L560,150 L0,150Z" fill="url(#pc-af)"/>
<path class="ln" d="M0,118 L60,118 L90,110 L110,60 L130,90 L150,78 L170,95 L190,82 L210,100 L230,88 L250,98 L270,90 L290,100 L320,92 L340,102 L360,96 L380,60 L400,92 L420,72 L440,98 L470,104 L500,108 L530,110 L560,112" fill="none" stroke="#BFE3CE" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
<div class="pc-ax"><span>1 de jun</span><span>9 de set</span><span>18 de dez</span><span>28 de mar</span></div>
</div>
</div>
</section>
<!-- EVENTOS: filtro (data-f) + galeria. Cada figure é um evento; data-t = categoria do filtro -->
<section id="pc-eventos">
<h2>Ciência em praças, escolas e feiras de saúde</h2>
<p class="pc-intro">A extensão é o motor do GPAv em campo. Levamos o jogo “Fato ou Fake” e outras atividades interativas a parques, escolas e feiras, combatendo a desinformação em saúde com ciência baseada em evidências, cara a cara com o público.</p>
<div class="pc-chips" role="group" aria-label="Filtrar eventos">
<button type="button" class="pc-btn" aria-pressed="true" data-f="all">Todos</button>
<button type="button" class="pc-btn" aria-pressed="false" data-f="campus">No campus</button>
<button type="button" class="pc-btn" aria-pressed="false" data-f="rua">Ruas e parques</button>
<button type="button" class="pc-btn" aria-pressed="false" data-f="congresso">Congressos</button>
</div>
<div class="pc-gal">
<figure class="pc-fig" data-t="campus"><button type="button"><img src="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/img/evento-1.png" alt="Domingo no campus, setembro de 2025" loading="lazy" decoding="async" width="2226" height="1159"></button><figcaption><b>Domingo no campus</b><span>Set/2025 · UFMG</span></figcaption></figure>
<figure class="pc-fig" data-t="rua"><button type="button"><img src="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/img/evento-2.png" alt="UFMG nas Ruas, outubro de 2025" loading="lazy" decoding="async" width="2221" height="1100"></button><figcaption><b>UFMG nas Ruas</b><span>Out/2025 · Praça da Liberdade</span></figcaption></figure>
<figure class="pc-fig" data-t="congresso"><button type="button"><img src="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/img/evento-3.png" alt="Simpósios de doenças negligenciadas, junho de 2026" loading="lazy" decoding="async" width="2220" height="1124"></button><figcaption><b>II World Symposium on Neglected Diseases e Simpósio Brasileiro de Doenças Negligenciadas</b><span>Jun/2026 · UFLA</span></figcaption></figure>
<figure class="pc-fig" data-t="campus"><button type="button"><img src="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/img/evento-4.png" alt="Domingo no campus, junho de 2026" loading="lazy" decoding="async" width="2220" height="1124"></button><figcaption><b>Domingo no campus</b><span>Jun/2026 · UFMG</span></figcaption></figure>
<figure class="pc-fig" data-t="rua"><button type="button"><img src="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/img/evento-5.png" alt="Projeto Ambientar, julho de 2026" loading="lazy" decoding="async" width="2220" height="1124"></button><figcaption><b>Projeto Ambientar</b><span>Jul/2026 · Parque Marcos Mazzoni, com Fiocruz Minas e Prefeitura de BH</span></figcaption></figure>
<figure class="pc-fig" data-t="campus"><button type="button"><img src="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/img/evento-6.png" alt="Domingo no campus, setembro de 2026" loading="lazy" decoding="async" width="2220" height="1223"></button><figcaption><b>Domingo no campus</b><span>Set/2026 · UFMG</span></figcaption></figure>
</div>
</section>
<!-- PLANOS: os cartões são montados pelo script (lista T em parcerias.js). O simulador calcula o total e gera o e-mail/WhatsApp. -->
<section id="pc-planos">
<h2>Escolha o nível de contrapartida da sua marca</h2>
<div class="pc-tiers" id="pc-tiers"></div>
<div class="pc-sim">
<div>
<label for="pc-emp">Nome da sua empresa</label>
<input type="text" id="pc-emp" placeholder="Ex.: Laboratório Exemplo" autocomplete="organization">
<label for="pc-mes">Duração da parceria: <span id="pc-mv">6</span> meses</label>
<input type="range" id="pc-mes" min="3" max="12" value="6">
</div>
<div>
<div class="pc-muted" id="pc-ts"></div>
<div class="pc-tot" id="pc-tt"></div>
<div class="pc-muted">investimento total no período</div>
<div class="pc-actions"><a class="pc-btn main" id="pc-bm" href="#" target="_blank" rel="noopener">Enviar por e-mail</a><a class="pc-btn" id="pc-bw" href="#" target="_blank" rel="noopener">WhatsApp</a></div>
<p class="pc-alt">Prefere outro programa? <a id="pc-bd" href="#">Abrir no app de e-mail padrão</a></p>
</div>
</div>
</section>
<!-- INVESTIMENTO: lista de usos do recurso; ao lado, logo e frase de impacto -->
<section id="pc-investimento">
<div class="pc-inv">
<div>
<h2>Cada real vira ciência em campo</h2>
<ul><li>Formação de estudantes extensionistas</li><li>Produção de materiais educativos</li><li>Brindes para ações presenciais</li><li>Jogos educativos e atividades interativas</li><li>Produção contínua de conteúdo científico</li><li>Ampliação das ações em escolas e eventos</li></ul>
</div>
<div class="pc-quote">
<img src="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/img/gpav-logo.png" alt="Logo do GPAv" loading="lazy">
<blockquote>Sua marca levando ciência baseada em evidências a milhares de pessoas, formando estudantes e combatendo a desinformação em saúde.</blockquote>
</div>
</div>
</section>
<!-- CONTATO E PARCEIROS (o endereço e os logos do ICB/UFMG ficam no rodapé do site) -->
<section id="pc-contato">
<div class="pc-contact">
<div class="pc-ctop">
<div>
<h2>Vamos conversar?</h2>
<p>Entre em contato e seja um parceiro estratégico do GPAv.</p>
</div>
<div class="pc-contacts">
<a href="https://instagram.com/gpav.ufmg" target="_blank" rel="noopener"><small>Instagram</small>@gpav.ufmg</a>
<a href="https://mail.google.com/mail/?view=cm&amp;fs=1&amp;to=contatogpav.ufmg@gmail.com&amp;su=Parceria%20GPAv" target="_blank" rel="noopener"><small>E-mail</small>contatogpav.ufmg@gmail.com</a>
<a href="https://wa.me/5531999928910?text=Ol%C3%A1!%20Vim%20pelo%20site%20do%20GPAv%20e%20gostaria%20de%20saber%20mais%20sobre%20a%20parceria." target="_blank" rel="noopener"><small>WhatsApp</small>+55 31 99992-8910</a>
</div>
</div>
<div class="pc-partw">
<p>Apoio e parceiros</p>
<div class="pc-part">
<span><img src="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/img/parceiro-1.png" alt="INCT Dengue e Interação Parasito-Hospedeiro" loading="lazy"></span>
<span><img src="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/img/parceiro-2.png" alt="CT Terapias Avançadas e Inovadoras, UFMG" loading="lazy"></span>
</div>
</div>
</div>
</section>
<!-- JANELA DE FOTO AMPLIADA (abre ao clicar numa foto da galeria) -->
<dialog class="pc-lb" id="pc-lb"><img alt=""><button type="button" class="pc-btn main" id="pc-lbx">Fechar</button></dialog>
</div>

<script src="{{ site.baseurl }}/gpav-wordpress/wp-content/plugins/gpav-content/parcerias/parcerias.js"></script>
