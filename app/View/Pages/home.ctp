<?php
/**
 * Página Landing - Blog to Dev
 * Standalone marketing page apresentando a plataforma
 * Sem integração com sistema de autenticação
 */
$this->layout = 'landing';
?>

<style>
    body {
        background-color: #fff;
    }
    
    .hero-section {
        background: linear-gradient(135deg, #000 0%, #1a1a1a 100%);
        color: white;
        padding: 80px 0;
        margin-bottom: 60px;
    }
    
    .hero-section h1 {
        font-size: 3.5rem;
        font-weight: 900;
        letter-spacing: -1px;
        background: linear-gradient(90deg, #fff 0%, #ccc 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 24px;
    }
    
    .hero-section .lead {
        font-size: 1.25rem;
        line-height: 1.8;
        opacity: 0.95;
    }
    
    .btn-light {
        background-color: #fff;
        color: #000;
        font-weight: 600;
        padding: 12px 24px;
        border: none;
    }
    
    .btn-light:hover {
        background-color: #f0f0f0;
        color: #000;
    }
    
    .btn-outline-light {
        border: 2px solid #fff;
        color: #fff;
        font-weight: 600;
        padding: 10px 22px;
        background: transparent;
    }
    
    .btn-outline-light:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-color: #fff;
        color: #fff;
    }
    
    .card-hover {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15) !important;
    }
    
    .feature-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #000 0%, #333 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        margin-bottom: 12px;
    }
    
    .step-number {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #000 0%, #333 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 20px;
    }
    
    .tech-badge {
        display: inline-block;
        background: #000;
        color: #fff;
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
        margin: 4px 4px 4px 0;
    }
    
    .section-divider {
        height: 2px;
        background: linear-gradient(90deg, #000 0%, #ccc 50%, transparent 100%);
        margin: 60px 0;
    }
    
    .btn-dark {
        background-color: #000;
        color: #fff;
        font-weight: 600;
    }
    
    .btn-dark:hover {
        background-color: #333;
        color: #fff;
    }
    
    .btn-outline-dark {
        border: 2px solid #000;
        color: #000;
        font-weight: 600;
        background: transparent;
    }
    
    .btn-outline-dark:hover {
        background-color: #000;
        color: #fff;
        border-color: #000;
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container-lg">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h1>Blog to Dev</h1>
                <p class="lead mb-4">
                    Uma plataforma moderna para compartilhar conhecimento técnico. 
                    Explore posts, publique suas ideias e conecte com a comunidade.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="/posts" class="btn btn-light">→ Explorar Posts</a>
                    <a href="/users/register" class="btn btn-outline-light">↳ Cadastrar</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-white p-5 rounded-3 shadow-lg">
                    <div class="text-center mb-4">
                        <div style="font-size: 48px; margin-bottom: 16px;">💻</div>
                        <h3 class="text-dark mb-3 fw-bold">Desenvolvimento Prático</h3>
                        <p class="text-muted small">
                            Conteúdo focado em soluções reais, boas práticas e arquitetura moderna.
                        </p>
                    </div>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        <span class="tech-badge">CakePHP</span>
                        <span class="tech-badge">PHP 7.4</span>
                        <span class="tech-badge">PostgreSQL</span>
                        <span class="tech-badge">Docker</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-divider"></div>

<!-- Features Section -->
<section id="features" class="py-5">
    <div class="container-lg">
        <h2 class="h4 mb-5 text-center fw-bold">Recursos Principais</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mx-auto">📝</div>
                        <h5 class="card-title fw-bold">Publicar Posts</h5>
                        <p class="card-text text-muted small">
                            Compartilhe seus conhecimentos como rascunho ou publicado para a comunidade.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mx-auto">🔍</div>
                        <h5 class="card-title fw-bold">Busca Avançada</h5>
                        <p class="card-text text-muted small">
                            Filtre posts por título, conteúdo, autor e período para encontrar exatamente o que procura.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mx-auto">🔐</div>
                        <h5 class="card-title fw-bold">Seguro</h5>
                        <p class="card-text text-muted small">
                            Autenticação robusta, controle de acesso granular e proteção de dados.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="feature-icon mx-auto">👥</div>
                        <h5 class="card-title fw-bold">Comunidade</h5>
                        <p class="card-text text-muted small">
                            Conecte-se com desenvolvedores, arquitetos e entusiastas de tecnologia.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-divider"></div>

<!-- Technology Stack -->
<section class="py-5 bg-light rounded-3">
    <div class="container-lg">
        <h2 class="h4 mb-5 text-center fw-bold">Stack Tecnológico</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 h-100">
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-3">🔧 Backend</h6>
                        <ul class="list-unstyled small text-muted">
                            <li>✓ CakePHP 2.x</li>
                            <li>✓ PHP 7.4</li>
                            <li>✓ PostgreSQL 12</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 h-100">
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-3">🎨 Frontend</h6>
                        <ul class="list-unstyled small text-muted">
                            <li>✓ Bootstrap 5.3</li>
                            <li>✓ jQuery 3.6</li>
                            <li>✓ HTML5 / CSS3</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 h-100">
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-3">🚀 Infraestrutura</h6>
                        <ul class="list-unstyled small text-muted">
                            <li>✓ Docker</li>
                            <li>✓ Docker Compose</li>
                            <li>✓ Apache HTTP</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 h-100">
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-3">🛡️ Segurança</h6>
                        <ul class="list-unstyled small text-muted">
                            <li>✓ Autenticação Blowfish</li>
                            <li>✓ Controle de acesso</li>
                            <li>✓ Soft delete</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-divider"></div>

<!-- Why Choose Section -->
<section class="py-5">
    <div class="container-lg">
        <h2 class="h4 mb-5 text-center fw-bold">Por que Blog to Dev?</h2>
        <div class="row g-5">
            <div class="col-md-6">
                <div class="d-flex mb-4">
                    <div class="me-3" style="flex-shrink: 0;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #000 0%, #333 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">✓</div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-2">Comunidade Ativa</h5>
                        <p class="text-muted small">Conecte-se com desenvolvedores, arquitetos e entusiastas de tecnologia do Brasil e mundo.</p>
                    </div>
                </div>
                <div class="d-flex mb-4">
                    <div class="me-3" style="flex-shrink: 0;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #000 0%, #333 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">✓</div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-2">Conteúdo de Qualidade</h5>
                        <p class="text-muted small">Posts aprofundados sobre desenvolvimento, DevOps, arquitetura, segurança e inovação.</p>
                    </div>
                </div>
                <div class="d-flex">
                    <div class="me-3" style="flex-shrink: 0;">
                        <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #000 0%, #333 100%); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">✓</div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-2">100% Gratuito</h5>
                        <p class="text-muted small">Sem custo para ler ou publicar. Compartilhe conhecimento livremente com a comunidade.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-dark p-5 rounded-3 text-white">
                    <h4 class="mb-3 fw-bold">Estatísticas da Plataforma</h4>
                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <div class="text-center">
                                <div style="font-size: 32px; font-weight: bold; color: #fff;">40+</div>
                                <p class="text-light small mb-0">Posts Publicados</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <div style="font-size: 32px; font-weight: bold; color: #fff;">9</div>
                                <p class="text-light small mb-0">Autores Ativos</p>
                            </div>
                        </div>
                    </div>
                    <p class="text-light small mb-0">
                        A plataforma cresceu rapidamente com conteúdo de qualidade produzido por profissionais experientes.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-divider"></div>

<!-- Getting Started Section -->
<section id="getting-started" class="py-5">
    <div class="container-lg">
        <h2 class="h4 mb-5 text-center fw-bold">Como Começar</h2>
        <div class="row g-4">
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="step-number mb-3">1</div>
                        <h5 class="card-title fw-bold">Explore</h5>
                        <p class="card-text text-muted small">
                            Navegue pelos posts publicados e descubra conteúdo técnico relevante.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="step-number mb-3">2</div>
                        <h5 class="card-title fw-bold">Registre-se</h5>
                        <p class="card-text text-muted small">
                            Crie sua conta gratuita em alguns cliques para acessar todos os recursos.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="step-number mb-3">3</div>
                        <h5 class="card-title fw-bold">Publique</h5>
                        <p class="card-text text-muted small">
                            Compartilhe seus posts como rascunho ou publicado para a comunidade.
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3">
                <div class="card card-hover border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="step-number mb-3">4</div>
                        <h5 class="card-title fw-bold">Conecte</h5>
                        <p class="card-text text-muted small">
                            Interaja com a comunidade de desenvolvedores e aprenda juntos.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="section-divider"></div>

<!-- CTA Section -->
<section class="py-5 bg-light">
    <div class="container-lg text-center">
        <h2 class="h4 mb-4 fw-bold">Pronto para começar?</h2>
        <p class="lead text-muted mb-4" style="max-width: 600px; margin-left: auto; margin-right: auto;">
            Junte-se à comunidade de desenvolvedores e comece a compartilhar seu conhecimento com o mundo.
        </p>
        <div class="d-flex flex-wrap gap-3 justify-content-center">
            <a href="/posts" class="btn btn-dark btn-lg">→ Explorar Posts</a>
            <a href="/users/register" class="btn btn-outline-dark btn-lg">↳ Cadastrar</a>
        </div>
    </div>
</section>
