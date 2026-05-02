import { Helmet } from "react-helmet-async";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { Database, CheckCircle, ArrowRight, Zap, Shield, RefreshCw, BarChart } from "lucide-react";
import { Link } from "react-router-dom";

const SolucaoBancoDados = () => {
  const benefits = [
    {
      icon: Zap,
      title: "Performance Otimizada",
      description: "Bancos de dados otimizados para consultas rápidas e eficientes."
    },
    {
      icon: Shield,
      title: "Segurança dos Dados",
      description: "Proteção robusta com criptografia, controle de acesso e auditoria."
    },
    {
      icon: RefreshCw,
      title: "Backup Automatizado",
      description: "Cópias de segurança automáticas para garantir a integridade dos dados."
    },
    {
      icon: BarChart,
      title: "Análise de Dados",
      description: "Estrutura preparada para relatórios e análises gerenciais."
    }
  ];

  const features = [
    "Otimização de consultas",
    "Backup e recuperação",
    "Replicação e alta disponibilidade",
    "Monitoramento de performance",
    "Administração contínua",
    "Suporte técnico especializado"
  ];

  return (
    <>
      <Helmet>
        <title>Banco de Dados | SASN Tech - Castanhal PA</title>
        <meta name="description" content="Soluções em banco de dados em Castanhal PA. Modelagem, otimização, migração e administração de bancos de dados para sua empresa." />
        <meta name="keywords" content="banco de dados Castanhal, DBA Pará, migração de dados, otimização SQL, administração banco dados" />
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
                  <Database className="w-10 h-10 text-primary" />
                </div>
                
                <h1 className="text-4xl lg:text-6xl font-bold text-foreground mb-6">
                  Banco de <span className="text-primary">Dados</span>
                </h1>
                
                <p className="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
                  Dados são o coração da sua empresa. Oferecemos soluções completas para 
                  armazenamento, gestão e proteção das suas informações.
                </p>
                
                <a href="https://wa.me/5594991534873?text=Olá! Gostaria de um orçamento para o meu banco de dados."
                  target="_blank"
                  rel="noopener noreferrer">
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
                  Por que cuidar do seu <span className="text-primary">banco de dados</span>?
                </h2>
                <p className="text-muted-foreground text-lg">
                  Um banco de dados bem estruturado é essencial para a tomada de decisões e o crescimento do negócio.
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
              <a
              href="https://wa.me/5594991534873?text=Olá! Gostaria de um orçamento para otimizar meu banco de dados."
              target="_blank"
              rel="noopener noreferrer"
            >
              <Button variant="hero" size="lg">
                Solicitar Orçamento
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

export default SolucaoBancoDados;
