import { CheckCircle2, Target, Users, Zap } from "lucide-react";

const About = () => {
  const features = [
    {
      icon: Target,
      title: "Foco em Resultados",
      description: "Entregamos soluções que geram impacto real no seu negócio.",
    },
    {
      icon: Users,
      title: "Atendimento Personalizado",
      description: "Cada cliente é único. Criamos soluções sob medida para você.",
    },
    {
      icon: Zap,
      title: "Tecnologia de Ponta",
      description: "Utilizamos as melhores ferramentas e tecnologias do mercado.",
    },
  ];

  const benefits = [
    "Mais de 15 anos de experiência no mercado",
    "Equipe qualificada e atualizada",
    "Suporte técnico especializado",
    "Projetos entregues no prazo",
    "Soluções escaláveis e seguras",
    "Preços competitivos",
  ];

  return (
    <section id="sobre" className="py-24 bg-background relative overflow-hidden">
      {/* Background decorations */}
      <div className="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-primary/5 to-transparent" />
      
      <div className="container mx-auto px-4 relative z-10">
        <div className="grid lg:grid-cols-2 gap-16 items-center">
          {/* Left content */}
          <div>
            <span className="text-primary font-semibold text-sm tracking-wider uppercase mb-4 block">
              Sobre Nós
            </span>
            <h2 className="text-3xl lg:text-5xl font-bold text-foreground mb-6">
              Consultoria em TI que{" "}
              <span className="text-primary">transforma negócios</span>
            </h2>
            <p className="text-muted-foreground text-lg mb-8 leading-relaxed">
              O principal papel de uma consultoria de Tecnologia da Informação é encontrar 
              as melhores soluções tecnológicas para os desafios enfrentados pela sua empresa. 
              Acompanhamos a implantação das ferramentas e avaliamos os resultados para garantir 
              o sucesso do seu projeto.
            </p>

            {/* Benefits list */}
            <div className="grid sm:grid-cols-2 gap-4">
              {benefits.map((benefit) => (
                <div key={benefit} className="flex items-center gap-3">
                  <CheckCircle2 className="w-5 h-5 text-primary flex-shrink-0" />
                  <span className="text-foreground text-sm">{benefit}</span>
                </div>
              ))}
            </div>
          </div>

          {/* Right content - Feature cards */}
          <div className="space-y-6">
            {features.map((feature, index) => (
              <div
                key={feature.title}
                className={`p-6 rounded-2xl bg-card border border-border hover:border-primary/50 transition-all duration-300 group animate-fade-in-up opacity-0 shadow-card`}
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <div className="flex items-start gap-5">
                  <div className="w-14 h-14 rounded-xl bg-primary/10 flex items-center justify-center group-hover:bg-primary group-hover:text-primary-foreground transition-colors duration-300">
                    <feature.icon className="w-7 h-7 text-primary group-hover:text-primary-foreground transition-colors duration-300" />
                  </div>
                  <div>
                    <h3 className="text-xl font-semibold text-foreground mb-2">
                      {feature.title}
                    </h3>
                    <p className="text-muted-foreground">{feature.description}</p>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>
    </section>
  );
};

export default About;