import { Globe, Code, Server, Shield, Database, Smartphone } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Link } from "react-router-dom";

const Solutions = () => {
  const solutions = [
    {
      icon: Globe,
      title: "Websites",
      description:
        "Desenvolvimento e criação de sites responsivos com layout exclusivo, formulário de contato, SEO otimizado e integração com redes sociais.",
      features: ["Design Responsivo", "SEO Otimizado", "Formulários", "Landing Pages"],
      link: "/solucoes/websites"
    },
    {
      icon: Code,
      title: "Sistemas Personalizados",
      description:
        "Desenvolvimento de sistemas sob medida para automatizar processos, gerenciar dados e otimizar a operação da sua empresa.",
      features: ["Sistemas Web", "ERP Simples", "Automação"],
      link: "/solucoes/sistemas"
    },
    {
      icon: Server,
      title: "Servidores",
      description:
        "Firewall, proteção de rede interna contra acessos não autorizados, servidor de arquivos, intranet e infraestrutura de TI.",
      features: ["Firewall", "Servidor de Arquivos", "Intranet", "VPN"],
      link: "/solucoes/servidores"
    },
    {
      icon: Shield,
      title: "Segurança",
      description:
        "Implementação de políticas de segurança, backup automatizado, antivírus corporativo e monitoramento de rede.",
      features: ["Backup", "Antivírus", "Monitoramento", "Políticas"],
      link: "/solucoes/seguranca"
    },
    {
      icon: Database,
      title: "Banco de Dados",
      description:
        "Modelagem, implementação e manutenção de bancos de dados para armazenamento seguro e eficiente de informações.",
      features: ["Modelagem", "Otimização", "Migração", "Backup"],
      link: "/solucoes/banco-dados"
    },
    {
      icon: Smartphone,
      title: "Suporte Técnico",
      description:
        "Montagem e manutenção de desktops e notebooks, instalação de sistemas operacionais e programas, suporte remoto e presencial.",
      features: ["Manutenção", "Instalação", "Suporte Remoto", "Upgrade"],
      link: "/solucoes/suporte-tecnico"
    },
  ];

  return (
    <section id="solucoes" className="py-24 bg-muted/50 relative overflow-hidden">
      <div className="absolute inset-0 bg-grid-pattern opacity-30" />

      <div className="container mx-auto px-4 relative z-10">
        <div className="text-center max-w-3xl mx-auto mb-16">
          <span className="text-primary font-semibold text-sm tracking-wider uppercase mb-4 block">
            Nossas Soluções
          </span>
          <h2 className="text-3xl lg:text-5xl font-bold text-foreground mb-6">
            Soluções completas em{" "}
            <span className="text-primary">tecnologia</span>
          </h2>
          <p className="text-muted-foreground text-lg">
            Oferecemos uma gama completa de serviços para atender todas as necessidades 
            tecnológicas da sua empresa.
          </p>
        </div>

        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          {solutions.map((solution, index) => (
            <div
              key={solution.title}
              className="group p-8 rounded-2xl bg-card border border-border hover:border-primary/50 transition-all duration-500 hover:shadow-glow animate-fade-in-up opacity-0"
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              <div className="w-16 h-16 rounded-2xl bg-primary/10 flex items-center justify-center mb-6 group-hover:bg-primary transition-all duration-300">
                <solution.icon className="w-8 h-8 text-primary group-hover:text-primary-foreground transition-colors duration-300" />
              </div>

              <h3 className="text-xl font-bold text-foreground mb-4">
                {solution.title}
              </h3>
              <p className="text-muted-foreground mb-6 leading-relaxed">
                {solution.description}
              </p>

              <div className="flex flex-wrap gap-2 mb-6">
                {solution.features.map((feature) => (
                  <span
                    key={feature}
                    className="px-3 py-1 text-xs font-medium bg-muted text-muted-foreground rounded-full border border-border"
                  >
                    {feature}
                  </span>
                ))}
              </div>

              <Link to={solution.link}>
                <Button variant="ghost" className="group/btn p-0 h-auto font-semibold text-primary hover:text-primary/80 hover:bg-transparent">
                  Saiba mais
                  <span className="ml-2 transition-transform duration-300 group-hover/btn:translate-x-1">
                    →
                  </span>
                </Button>
              </Link>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Solutions;
