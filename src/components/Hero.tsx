import { Button } from "@/components/ui/button";
import { ArrowRight, Code2, Server, Monitor } from "lucide-react";

const Hero = () => {
  return (
    <section
      id="inicio"
      className="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-b from-white via-gray-50 to-gray-100"
    >
      {/* Background Effects */}
      <div className="absolute inset-0 bg-grid-pattern opacity-30" />
      
      {/* Subtle gradient overlays */}
      <div className="absolute top-0 left-0 right-0 h-40 bg-gradient-to-b from-white to-transparent" />
      <div className="absolute top-1/4 left-1/4 w-96 h-96 bg-primary/5 rounded-full blur-3xl animate-float" />
      <div className="absolute bottom-1/4 right-1/4 w-80 h-80 bg-gray-200/50 rounded-full blur-3xl animate-float animation-delay-300" />

      {/* Floating tech elements */}
      <div className="absolute top-1/3 left-10 lg:left-20 opacity-20 animate-float animation-delay-200">
        <Code2 size={48} className="text-primary" />
      </div>
      <div className="absolute top-1/2 right-10 lg:right-32 opacity-20 animate-float animation-delay-400">
        <Server size={40} className="text-primary" />
      </div>
      <div className="absolute bottom-1/3 left-1/4 opacity-20 animate-float animation-delay-100">
        <Monitor size={36} className="text-primary" />
      </div>

      <div className="container mx-auto px-4 relative z-10">
        <div className="max-w-4xl mx-auto text-center">
          {/* Badge */}
          <div className="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/80 border border-gray-200 shadow-sm mb-8 animate-fade-in backdrop-blur-sm">
            <span className="w-2 h-2 rounded-full bg-primary animate-pulse" />
            <span className="text-sm text-gray-600">Soluções em Tecnologia da Informação</span>
          </div>

          {/* Main Heading */}
          <h1 className="text-4xl sm:text-5xl lg:text-7xl font-bold text-gray-900 mb-6 animate-fade-in-up opacity-0 animation-delay-100">
            Transformamos ideias em{" "}
            <span className="text-primary">soluções digitais</span>
          </h1>

          {/* Subtitle */}
          <p className="text-lg sm:text-xl text-gray-600 max-w-2xl mx-auto mb-10 animate-fade-in-up opacity-0 animation-delay-200">
            Desenvolvimento de websites, sistemas personalizados e consultoria em TI 
            para impulsionar sua empresa no mundo digital.
          </p>

          {/* CTA Buttons */}
          <div className="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up opacity-0 animation-delay-300">
            <a href="#contato">
              <Button variant="hero" size="xl">
                Fale Conosco
                <ArrowRight className="ml-2" />
              </Button>
            </a>
            <a href="#solucoes">
              <Button variant="heroOutline" size="xl">
                Nossas Soluções
              </Button>
            </a>
          </div>

          {/* Stats */}
          <div className="grid grid-cols-2 md:grid-cols-4 gap-8 mt-20 animate-fade-in-up opacity-0 animation-delay-400">
            {[
              { value: "10+", label: "Anos de Experiência" },
              { value: "50+", label: "Projetos Entregues" },
              { value: "100%", label: "Satisfação" },
              { value: "24/7", label: "Suporte" },
            ].map((stat) => (
              <div key={stat.label} className="text-center">
                <div className="text-3xl lg:text-4xl font-bold text-primary mb-2">
                  {stat.value}
                </div>
                <div className="text-sm text-gray-500">{stat.label}</div>
              </div>
            ))}
          </div>
        </div>
      </div>

      {/* Bottom gradient */}
      <div className="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-gray-800 to-transparent" />
    </section>
  );
};

export default Hero;