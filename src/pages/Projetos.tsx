import { Helmet } from "react-helmet-async";
import { ExternalLink, ArrowLeft } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";
import Header from "@/components/Header";
import Footer from "@/components/Footer";

const Projetos = () => {
  const projects = [
    {
      title: "Projeto Emanuel",
      category: "Website Institucional",
      description: "O site apresenta uma iniciativa social que oferece reforço escolar. Alimentação balanceada e atividades recreativas para crianças da Agrovila Castelo Branco, além de apoio às famílias da comunidade.",
      url: "https://projetoemanuel.com.br/",
      image: "https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=600&h=400&fit=crop",
      technologies: ["React", "Node.js", "PostgreSQL", "Tailwind CSS"],
    },
    {
      title: "PIB Apêu",
      category: "Website Institucional",
      description: "Site institucional para igreja com sistema de eventos e notícias. Inclui galeria de fotos, calendário de eventos, sistema de doações online e área administrativa para gestão de conteúdo.",
      url: "https://pibapeu.com.br/",
      image: "https://images.unsplash.com/photo-1438032005730-c779502df39b?w=600&h=400&fit=crop",
      technologies: ["WordPress", "PHP", "MySQL", "CSS"],
    },
    {
      title: "Sistema de Gestão Empresarial",
      category: "Sistema Web",
      description: "Sistema personalizado para gerenciamento de processos empresariais. Inclui módulos de controle financeiro, gestão de estoque, relatórios gerenciais e dashboard em tempo real.",
      url: "#",
      image: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop",
      technologies: ["React", "TypeScript", "Supabase", "Chart.js"],
    },
    {
      title: "E-commerce Regional",
      category: "Loja Virtual",
      description: "Plataforma de vendas online com integração de pagamentos. Sistema completo com carrinho de compras, checkout seguro, painel administrativo e integração com correios.",
      url: "#",
      image: "https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop",
      technologies: ["Next.js", "Stripe", "PostgreSQL", "Tailwind CSS"],
    },
    {
      title: "Portal de Notícias",
      category: "Website",
      description: "Portal de notícias regional com sistema de publicação, categorias, comentários e integração com redes sociais. Design responsivo otimizado para leitura.",
      url: "#",
      image: "https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600&h=400&fit=crop",
      technologies: ["WordPress", "PHP", "MySQL", "JavaScript"],
    },
    {
      title: "App de Delivery",
      category: "Aplicação Web",
      description: "Sistema de pedidos online para restaurantes locais. Inclui cardápio digital, sistema de pedidos em tempo real, painel do restaurante e tracking de entregas.",
      url: "#",
      image: "https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&h=400&fit=crop",
      technologies: ["React", "Node.js", "Socket.io", "MongoDB"],
    },
  ];

  return (
    <>
      <Helmet>
        <title>Projetos | SASN Tecnologia - Portfólio de Trabalhos</title>
        <meta name="description" content="Conheça os projetos desenvolvidos pela SASN Tecnologia. Websites, sistemas, e-commerce e soluções personalizadas para empresas em Castanhal e região." />
      </Helmet>

      <Header />

      <main className="pt-20">
        {/* Hero Section */}
        <section className="py-16 bg-gradient-to-b from-white via-gray-50 to-gray-100">
          <div className="container mx-auto px-4">
            <Link to="/" className="inline-flex items-center gap-2 text-primary hover:gap-3 transition-all mb-8">
              <ArrowLeft className="w-4 h-4" />
              Voltar ao início
            </Link>
            
            <div className="max-w-3xl">
              <span className="text-primary font-semibold text-sm tracking-wider uppercase mb-4 block">
                Portfólio
              </span>
              <h1 className="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                Nossos <span className="text-primary">Projetos</span>
              </h1>
              <p className="text-gray-600 text-lg">
                Conheça alguns dos projetos que desenvolvemos para nossos clientes. 
                Cada solução é única e personalizada para atender às necessidades específicas de cada empresa.
              </p>
            </div>
          </div>
        </section>

        {/* Projects Grid */}
        <section className="py-16 bg-background">
          <div className="container mx-auto px-4">
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
              {projects.map((project, index) => (
                <div
                  key={project.title}
                  className="group rounded-2xl overflow-hidden bg-card border border-border hover:border-primary/50 transition-all duration-500 animate-fade-in-up opacity-0 shadow-card"
                  style={{ animationDelay: `${index * 0.1}s` }}
                >
                  {/* Image */}
                  <div className="relative h-48 overflow-hidden">
                    <img
                      src={project.image}
                      alt={project.title}
                      className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-foreground/90 via-foreground/40 to-transparent" />
                    
                    {/* Category badge */}
                    <span className="absolute top-4 left-4 px-3 py-1 text-xs font-medium bg-primary text-primary-foreground rounded-full">
                      {project.category}
                    </span>
                  </div>

                  {/* Content */}
                  <div className="p-6">
                    <h3 className="text-xl font-bold text-foreground mb-2">
                      {project.title}
                    </h3>
                    <p className="text-muted-foreground mb-4 text-sm line-clamp-3">
                      {project.description}
                    </p>
                    
                    {/* Technologies */}
                    <div className="flex flex-wrap gap-2 mb-4">
                      {project.technologies.map((tech) => (
                        <span
                          key={tech}
                          className="px-2 py-1 text-xs bg-muted text-muted-foreground rounded"
                        >
                          {tech}
                        </span>
                      ))}
                    </div>
                    
                    {project.url !== "#" && (
                      <a
                        href={project.url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="inline-flex items-center gap-2 text-primary font-semibold hover:gap-3 transition-all duration-300"
                      >
                        Visitar site
                        <ExternalLink className="w-4 h-4" />
                      </a>
                    )}
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* CTA Section */}
        <section className="py-16 bg-muted/50">
          <div className="container mx-auto px-4 text-center">
            <h2 className="text-3xl font-bold text-foreground mb-4">
              Tem um projeto em mente?
            </h2>
            <p className="text-muted-foreground mb-8 max-w-2xl mx-auto">
              Entre em contato conosco e vamos transformar sua ideia em realidade.
            </p>
            <a
              href="https://wa.me/5594991534873?text=Olá! Gostaria de um orçamento para meu projeto."
              target="_blank"
              rel="noopener noreferrer"
            >
              <Button variant="hero" size="lg">
                Solicitar Orçamento
              </Button>
            </a>
          </div>
        </section>
      </main>

      <Footer />
    </>
  );
};

export default Projetos;
