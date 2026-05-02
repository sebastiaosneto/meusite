import { Helmet } from "react-helmet-async";
import Header from "@/components/Header";
import Footer from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { Code, CheckCircle, ArrowRight, Settings, TrendingUp, Clock, Shield } from "lucide-react";
import { Link } from "react-router-dom";

const SolucaoSistemas = () => {
  const benefits = [
    {
      icon: Settings,
      title: "Automação de Processos",
      description: "Elimine tarefas manuais repetitivas e reduza erros humanos com sistemas automatizados."
    },
    {
      icon: TrendingUp,
      title: "Aumento de Produtividade",
      description: "Otimize o tempo da sua equipe com ferramentas desenvolvidas para suas necessidades específicas."
    },
    {
      icon: Clock,
      title: "Economia de Tempo",
      description: "Processos que levavam horas podem ser realizados em minutos com o sistema certo."
    },
    {
      icon: Shield,
      title: "Segurança dos Dados",
      description: "Seus dados protegidos com as melhores práticas de segurança da informação."
    }
  ];

  const features = [
    "Sistemas web acessíveis de qualquer lugar",
    "App Web para dispositivos móveis",
    "Dashboards e relatórios personalizados",
    "Treinamento da equipe incluso",
    "Suporte técnico contínuo",
    "Atualizações e melhorias"
  ];

  return (
    <>
      <Helmet>
        <title>Sistemas Personalizados | SASN Tech - Castanhal PA</title>
        <meta name="description" content="Desenvolvimento de sistemas personalizados em Castanhal PA. ERP, CRM, automação de processos e software sob medida para sua empresa." />
        <meta name="keywords" content="sistemas personalizados Castanhal, software sob medida, ERP Pará, CRM, automação empresarial" />
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
                  <Code className="w-10 h-10 text-primary" />
                </div>
                
                <h1 className="text-4xl lg:text-6xl font-bold text-foreground mb-6">
                  Sistemas <span className="text-primary">Personalizados</span>
                </h1>
                
                <p className="text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
                  Cada empresa é única. Por isso desenvolvemos sistemas sob medida que se adaptam 
                  perfeitamente às suas necessidades e processos de negócio.
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
                  Por que investir em um <span className="text-primary">sistema personalizado</span>?
                </h2>
                <p className="text-muted-foreground text-lg">
                  Sistemas genéricos não atendem todas as necessidades da sua empresa. Um sistema sob medida é a solução ideal.
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
                  O que <span className="text-primary">desenvolvemos</span>?
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
                    Quero meu sistema
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

export default SolucaoSistemas;
