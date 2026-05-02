import { ExternalLink } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";

const Projects = () => {
  const projects = [
    {
      title: "Extrajud Cursos",
      category: "Website Institucional",
      description: "Plataforma de cursos online com design moderno e sistema de matrículas.",
      url: "https://extrajudcursos.com/",
      image: "https://images.unsplash.com/photo-1501504905252-473c47e087f8?w=600&h=400&fit=crop",
    },
    {
      title: "PIB Apêu",
      category: "Website Institucional",
      description: "Site institucional para igreja com sistema de eventos e notícias.",
      url: "https://pibapeu.com.br/",
      image: "https://images.unsplash.com/photo-1438032005730-c779502df39b?w=600&h=400&fit=crop",
    },
    {
      title: "Sistema de Gestão",
      category: "Sistema Web",
      description: "Sistema personalizado para gerenciamento de processos empresariais.",
      url: "#",
      image: "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&h=400&fit=crop",
    },
    {
      title: "E-commerce Local",
      category: "Loja Virtual",
      description: "Plataforma de vendas online com integração de pagamentos.",
      url: "#",
      image: "https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=400&fit=crop",
    },
  ];

  return (
    <section id="projetos" className="py-24 bg-background relative overflow-hidden">
      <div className="container mx-auto px-4 relative z-10">
        {/* Header */}
        <div className="text-center max-w-3xl mx-auto mb-16">
          <span className="text-primary font-semibold text-sm tracking-wider uppercase mb-4 block">
            Portfólio
          </span>
          <h2 className="text-3xl lg:text-5xl font-bold text-foreground mb-6">
            Projetos que{" "}
            <span className="text-primary">entregamos</span>
          </h2>
          <p className="text-muted-foreground text-lg">
            Conheça alguns dos projetos que desenvolvemos para nossos clientes.
          </p>
        </div>

        {/* Projects Grid */}
        <div className="grid md:grid-cols-2 gap-8">
          {projects.map((project, index) => (
            <div
              key={project.title}
              className="group relative rounded-2xl overflow-hidden bg-card border border-border hover:border-primary/50 transition-all duration-500 animate-fade-in-up opacity-0 shadow-card"
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              {/* Image */}
              <div className="relative h-64 overflow-hidden">
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
                <p className="text-muted-foreground mb-4">
                  {project.description}
                </p>
                
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

        {/* CTA */}
        <div className="text-center mt-12">
          <Link to="/projetos">
            <Button variant="heroOutline" size="lg">
              Ver todos os projetos
            </Button>
          </Link>
        </div>
      </div>
    </section>
  );
};

export default Projects;