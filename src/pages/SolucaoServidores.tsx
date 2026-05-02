import { Helmet } from "react-helmet-async";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { Server, CheckCircle, ArrowRight, Shield, Network, HardDrive, Lock } from "lucide-react";
import { Link } from "react-router-dom";

const SolucaoServidores = () => {
  const benefits = [
    {
      icon: Shield,
      title: "Proteção de Rede",
      description: "Firewall configurado para proteger sua rede interna contra acessos não autorizados."
    },
    {
      icon: Network,
      title: "Conectividade",
      description: "Infraestrutura de rede otimizada para máxima performance e estabilidade."
    },
    {
      icon: HardDrive,
      title: "Servidor de Arquivos",
      description: "Armazenamento centralizado e seguro para todos os arquivos da empresa."
    },
    {
      icon: Lock,
      title: "VPN Segura",
      description: "Acesso remoto seguro aos recursos da empresa de qualquer lugar."
    }
  ];

  const features = [
    "Firewall empresarial",
    "Servidor de arquivos centralizado",
    "Intranet corporativa",
    "VPN para acesso remoto",
    "Controle de acesso por usuário",
    "Monitoramento 24/7",
    "Backup automático",
    "Suporte técnico especializado"
  ];

  return (
    <>
      <Helmet>
        <title>Servidores e Infraestrutura | SASN Tech - Castanhal PA</title>
        <meta name="description" content="Implementação de servidores e infraestrutura de TI em Castanhal PA. Firewall, servidor de arquivos, VPN e intranet para sua empresa." />
        <meta name="keywords" content="servidores Castanhal, infraestrutura TI Pará, firewall empresarial, servidor de arquivos, VPN corporativa" />
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
                  <Server className="w-10 h-10 text-primary" />
                </div>
                
                <h1 className="text-4xl lg:text-6xl font-bold text-foreground mb-6">
                  Servidores e <span className="text-primary">Infraestrutura</span>
                </h1>
                
                <p className="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
                  Uma infraestrutura de TI sólida é a base para o crescimento da sua empresa. 
                  Implementamos soluções robustas e escaláveis.
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
                  Por que investir em <span className="text-primary">infraestrutura</span>?
                </h2>
                <p className="text-muted-foreground text-lg">
                  Uma infraestrutura bem planejada aumenta a produtividade e protege os dados mais valiosos da sua empresa.
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
                    Quero melhorar minha infraestrutura
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

export default SolucaoServidores;
