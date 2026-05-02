import { Helmet } from "react-helmet-async";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { Globe, CheckCircle, ArrowRight, Monitor, Smartphone, Search, Zap } from "lucide-react";
import { Link } from "react-router-dom";

const SolucaoWebsites = () => {
  const benefits = [
    {
      icon: Monitor,
      title: "Presença Digital 24/7",
      description: "Seu negócio disponível para clientes a qualquer hora do dia, em qualquer lugar do mundo."
    },
    {
      icon: Smartphone,
      title: "Design Responsivo",
      description: "Sites que se adaptam perfeitamente a qualquer dispositivo: desktop, tablet ou smartphone."
    },
    {
      icon: Search,
      title: "SEO Otimizado",
      description: "Apareça nas primeiras posições do Google e atraia mais clientes organicamente."
    },
    {
      icon: Zap,
      title: "Carregamento Rápido",
      description: "Sites otimizados para carregar rapidamente, melhorando a experiência do usuário."
    }
  ];

  const features = [
    "Layout exclusivo e personalizado",
    "Formulário de contato integrado",
    "Integração com redes sociais",
    "Painel administrativo fácil de usar",
    "Certificado SSL (site seguro)",
    "Hospedagem inclusa no primeiro ano",
    "Suporte técnico especializado",
    "Otimização para mecanismos de busca"
  ];

  return (
    <>
      <Helmet>
        <title>Desenvolvimento de Websites | SASN Tech - Castanhal PA</title>
        <meta name="description" content="Criação de websites profissionais em Castanhal PA. Sites responsivos, otimizados para SEO, com design exclusivo. Atendemos toda a região." />
        <meta name="keywords" content="criação de sites Castanhal, desenvolvimento web Pará, sites responsivos, agência digital, websites profissionais" />
      </Helmet>

      <div className="min-h-screen bg-background">
        <Header />
        
        <main className="pt-20">
          {/* Hero Section */}
          <section className="py-24 bg-gradient-to-b from-white via-gray-50 to-gray-100 relative overflow-hidden">
            <div className="absolute inset-0 bg-grid-pattern opacity-30" />
            <div className="container mx-auto px-4 relative z-10">
              <Link to="/" className="inline-flex items-center text-primary hover:underline mb-8">
                ← Voltar para o início
              </Link>
              
              <div className="max-w-4xl mx-auto text-center">
                <div className="w-20 h-20 rounded-2xl bg-primary/10 flex items-center justify-center mx-auto mb-8">
                  <Globe className="w-10 h-10 text-primary" />
                </div>
                
                <h1 className="text-4xl lg:text-6xl font-bold text-foreground mb-6">
                  Desenvolvimento de <span className="text-primary">Websites</span>
                </h1>
                
                <p className="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
                  Ter um website profissional é essencial para sua empresa se destacar no mercado digital. 
                  Criamos sites que convertem visitantes em clientes.
                </p>
                
                <a href="/#contato">
                  <Button variant="hero" size="xl">
                    Solicitar Orçamento
                    <ArrowRight className="ml-2" />
                  </Button>
                </a>
              </div>
            </div>
          </section>

          {/* Why You Need Section */}
          <section className="py-24 bg-background">
            <div className="container mx-auto px-4">
              <div className="max-w-3xl mx-auto text-center mb-16">
                <h2 className="text-3xl lg:text-4xl font-bold text-foreground mb-6">
                  Por que sua empresa precisa de um <span className="text-primary">website</span>?
                </h2>
                <p className="text-muted-foreground text-lg">
                  Em um mundo cada vez mais digital, não ter presença online significa perder oportunidades de negócio todos os dias.
                </p>
              </div>

              <div className="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                {benefits.map((benefit, index) => (
                  <div
                    key={benefit.title}
                    className="p-6 rounded-2xl bg-card border border-border hover:border-primary/50 transition-all duration-300"
                  >
                    <div className="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center mb-4">
                      <benefit.icon className="w-7 h-7 text-primary" />
                    </div>
                    <h3 className="text-xl font-bold text-foreground mb-2">{benefit.title}</h3>
                    <p className="text-muted-foreground">{benefit.description}</p>
                  </div>
                ))}
              </div>
            </div>
          </section>

          {/* Features Section */}
          <section className="py-24 bg-muted/50">
            <div className="container mx-auto px-4">
              <div className="max-w-3xl mx-auto text-center mb-16">
                <h2 className="text-3xl lg:text-4xl font-bold text-foreground mb-6">
                  O que está <span className="text-primary">incluso</span>?
                </h2>
              </div>

              <div className="grid md:grid-cols-2 gap-4 max-w-3xl mx-auto">
                {features.map((feature) => (
                  <div key={feature} className="flex items-center gap-3 p-4 rounded-xl bg-card border border-border">
                    <CheckCircle className="w-5 h-5 text-primary flex-shrink-0" />
                    <span className="text-foreground">{feature}</span>
                  </div>
                ))}
              </div>

              <div className="text-center mt-12">
                <a href="/#contato">
                  <Button variant="hero" size="lg">
                    Quero meu website
                    <ArrowRight className="ml-2" />
                  </Button>
                </a>
              </div>
            </div>
          </section>
        </main>

        <Footer />
      </div>
    </>
  );
};

export default SolucaoWebsites;
