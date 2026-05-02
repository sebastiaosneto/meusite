import { Helmet } from "react-helmet-async";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { Smartphone, CheckCircle, ArrowRight, Wrench, Monitor, Headphones, Cpu } from "lucide-react";
import { Link } from "react-router-dom";

const SolucaoSuporteTecnico = () => {
  const benefits = [
    {
      icon: Wrench,
      title: "Manutenção Preventiva",
      description: "Evite problemas antes que eles aconteçam com manutenção programada."
    },
    {
      icon: Monitor,
      title: "Reparo de Equipamentos",
      description: "Conserto de desktops, notebooks e periféricos com agilidade."
    },
    {
      icon: Headphones,
      title: "Suporte Remoto",
      description: "Resolução rápida de problemas sem necessidade de deslocamento."
    },
    {
      icon: Cpu,
      title: "Upgrade de Hardware",
      description: "Melhore o desempenho dos seus equipamentos com upgrades estratégicos."
    }
  ];

  const features = [
    "Manutenção de desktops e notebooks",
    "Instalação de sistemas operacionais",
    "Instalação de programas e drivers",
    "Suporte remoto imediato",
    "Atendimento presencial",
    "Limpeza e manutenção preventiva",
    "Upgrade de memória e SSD",
    "Formatação e recuperação de dados"
  ];

  return (
    <>
      <Helmet>
        <title>Suporte Técnico | SASN Tech - Castanhal PA</title>
        <meta name="description" content="Suporte técnico em informática em Castanhal PA. Manutenção de computadores, instalação de sistemas, suporte remoto e presencial." />
        <meta name="keywords" content="suporte técnico Castanhal, manutenção computadores Pará, assistência técnica, formatação, upgrade" />
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
                  <Smartphone className="w-10 h-10 text-primary" />
                </div>
                
                <h1 className="text-4xl lg:text-6xl font-bold text-foreground mb-6">
                  Suporte <span className="text-primary">Técnico</span>
                </h1>
                
                <p className="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
                  Problemas técnicos podem paralisar sua empresa. Conte com nosso suporte 
                  especializado para manter tudo funcionando perfeitamente.
                </p>
                
                <a href="https://wa.me/5594991534873?text=Olá! Gostaria de um orçamento para Suporte Técnico."
                  target="_blank"
                  rel="noopener noreferrer">
                  <Button variant="hero" size="xl">
                    Solicitar Orçamento
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
                  Por que contar com <span className="text-primary">suporte técnico</span>?
                </h2>
                <p className="text-muted-foreground text-lg">
                  Equipamentos funcionando bem significam produtividade e menos dor de cabeça para sua equipe.
                </p>
              </div>

              <div className="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                {benefits.map((benefit) => (
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
                  O que <span className="text-primary">oferecemos</span>?
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
                  <a href="https://wa.me/5594991534873?text=Olá! Gostaria de um orçamento para Suporte Técnico."
                    target="_blank"
                    rel="noopener noreferrer">
                  <Button variant="hero" size="lg">
                    Preciso de suporte
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

export default SolucaoSuporteTecnico;
