import { Helmet } from "react-helmet-async";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { Shield, CheckCircle, ArrowRight, Lock, Eye, Database, AlertTriangle } from "lucide-react";
import { Link } from "react-router-dom";

const SolucaoSeguranca = () => {
  const benefits = [
    {
      icon: Lock,
      title: "Proteção Contra Ameaças",
      description: "Defesa em múltiplas camadas contra vírus, malware, ransomware e outras ameaças."
    },
    {
      icon: Database,
      title: "Backup Automatizado",
      description: "Seus dados sempre seguros com backups automáticos e recuperação garantida."
    },
    {
      icon: Eye,
      title: "Monitoramento Contínuo",
      description: "Vigilância 24/7 da sua rede para detectar e responder a incidentes rapidamente."
    },
    {
      icon: AlertTriangle,
      title: "Prevenção de Perdas",
      description: "Políticas de segurança que previnem vazamentos e perdas de dados sensíveis."
    }
  ];

  const features = [
    "Antivírus corporativo gerenciado",
    "Backup automático em nuvem",
    "Monitoramento de rede em tempo real",
    "Políticas de segurança personalizadas",
    "Firewall avançado",
    "Criptografia de dados",
    "Controle de acesso por níveis",
    "Relatórios de segurança periódicos"
  ];

  return (
    <>
      <Helmet>
        <title>Segurança da Informação | SASN Tech - Castanhal PA</title>
        <meta name="description" content="Soluções de segurança da informação em Castanhal PA. Antivírus, backup, monitoramento e políticas de segurança para proteger sua empresa." />
        <meta name="keywords" content="segurança da informação Castanhal, antivírus corporativo, backup empresarial, proteção de dados Pará" />
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
                  <Shield className="w-10 h-10 text-primary" />
                </div>
                
                <h1 className="text-4xl lg:text-6xl font-bold text-foreground mb-6">
                  Segurança da <span className="text-primary">Informação</span>
                </h1>
                
                <p className="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
                  Proteja o ativo mais valioso da sua empresa: a informação. 
                  Implementamos soluções completas de segurança para manter seus dados seguros.
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
                  Por que investir em <span className="text-primary">segurança</span>?
                </h2>
                <p className="text-muted-foreground text-lg">
                  Ataques cibernéticos estão cada vez mais frequentes. Proteger sua empresa é uma necessidade, não uma opção.
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
                <a href="/#contato">
                  <Button variant="hero" size="lg">
                    Proteger minha empresa
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

export default SolucaoSeguranca;
