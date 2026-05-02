import { Helmet } from "react-helmet-async";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { Headphones, CheckCircle, ArrowRight, Clock, Shield, Users, Zap } from "lucide-react";
import { Link } from "react-router-dom";

const SuporteCorporativo = () => {
  const benefits = [
    {
      icon: Clock,
      title: "Atendimento Prioritário",
      description: "Sua empresa na frente da fila. Resolução rápida para manter sua operação funcionando."
    },
    {
      icon: Shield,
      title: "Prevenção de Problemas",
      description: "Monitoramento proativo para identificar e resolver problemas antes que afetem seu negócio."
    },
    {
      icon: Users,
      title: "Equipe Dedicada",
      description: "Profissionais que conhecem sua infraestrutura e entendem suas necessidades específicas."
    },
    {
      icon: Zap,
      title: "Redução de Custos",
      description: "Planos mensais previsíveis que custam menos do que suporte avulso e emergencial."
    }
  ];

  const plans = [
    {
      title: "Básico",
      description: "Para pequenas empresas",
      features: [
        "Suporte remoto ilimitado",
        "Atendimento em horário comercial",
        "Manutenção preventiva mensal",
        "Tempo de resposta: até 4 horas"
      ]
    },
    {
      title: "Profissional",
      description: "Para empresas em crescimento",
      features: [
        "Tudo do plano Básico",
        "Atendimento presencial incluso",
        "Monitoramento de servidores",
        "Tempo de resposta: até 2 horas",
        "Backup gerenciado"
      ]
    },
    {
      title: "Enterprise",
      description: "Para grandes operações",
      features: [
        "Tudo do plano Profissional",
        "Suporte 24/7",
        "SLA garantido",
        "Tempo de resposta: até 30 min",
        "Gestor de conta dedicado",
        "Consultoria estratégica"
      ]
    }
  ];

  return (
    <>
      <Helmet>
        <title>Suporte Corporativo | SASN Tech - Castanhal PA</title>
        <meta name="description" content="Suporte corporativo de TI em Castanhal PA. Planos de suporte contínuo para empresas com atendimento prioritário e equipe dedicada." />
        <meta name="keywords" content="suporte corporativo Castanhal, contrato de TI, suporte empresarial, outsourcing TI Pará" />
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
                  <Headphones className="w-10 h-10 text-primary" />
                </div>
                
                <h1 className="text-4xl lg:text-6xl font-bold text-foreground mb-6">
                  Suporte <span className="text-primary">Corporativo</span>
                </h1>
                
                <p className="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
                  Sua empresa precisa de um parceiro de TI confiável. Com nosso suporte corporativo, 
                  você tem uma equipe especializada cuidando da sua tecnologia.
                </p>
                
                <a href="/#contato">
                  <Button variant="hero" size="xl">
                    Solicitar Proposta
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
                  Por que sua empresa precisa de <span className="text-primary">suporte contínuo</span>?
                </h2>
                <p className="text-muted-foreground text-lg">
                  Problemas de TI podem custar caro. Um suporte proativo mantém sua operação funcionando e seus custos sob controle.
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

          {/* Plans Section */}
          <section className="py-24 bg-muted/50">
            <div className="container mx-auto px-4">
              <div className="max-w-3xl mx-auto text-center mb-16">
                <h2 className="text-3xl lg:text-4xl font-bold text-foreground mb-6">
                  Nossos <span className="text-primary">Planos</span>
                </h2>
                <p className="text-muted-foreground text-lg">
                  Escolha o plano ideal para o tamanho e necessidades da sua empresa.
                </p>
              </div>

              <div className="grid md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                {plans.map((plan, index) => (
                  <div 
                    key={plan.title} 
                    className={`p-6 rounded-2xl bg-card border transition-all duration-300 ${
                      index === 1 ? 'border-primary shadow-glow' : 'border-border hover:border-primary/50'
                    }`}
                  >
                    <h3 className="text-xl font-bold text-foreground mb-1">{plan.title}</h3>
                    <p className="text-muted-foreground text-sm mb-6">{plan.description}</p>
                    
                    <ul className="space-y-3 mb-6">
                      {plan.features.map((feature) => (
                        <li key={feature} className="flex items-start gap-2">
                          <CheckCircle className="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                          <span className="text-foreground text-sm">{feature}</span>
                        </li>
                      ))}
                    </ul>
                    
                    <a href="/#contato">
                      <Button 
                        variant={index === 1 ? "hero" : "outline"} 
                        className="w-full"
                      >
                        Solicitar Proposta
                      </Button>
                    </a>
                  </div>
                ))}
              </div>
            </div>
          </section>

          {/* CTA Section */}
          <section className="py-24 bg-foreground">
            <div className="container mx-auto px-4 text-center">
              <h2 className="text-3xl lg:text-4xl font-bold text-background mb-6">
                Pronto para ter um parceiro de TI confiável?
              </h2>
              <p className="text-background/70 text-lg mb-8 max-w-2xl mx-auto">
                Entre em contato e receba uma proposta personalizada para as necessidades da sua empresa.
              </p>
              <a href="/#contato">
                <Button variant="secondary" size="xl" className="bg-background text-foreground hover:bg-background/90">
                  Falar com um Consultor
                  <ArrowRight className="ml-2" />
                </Button>
              </a>
            </div>
          </section>
        </main>

        <Footer />
      </div>
    </>
  );
};

export default SuporteCorporativo;
