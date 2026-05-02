import { Star, Quote } from "lucide-react";

const Testimonials = () => {
  const testimonials = [
    {
      name: "Everaldo Junior",
      role: "Pastor",
      company: "Projeto Emanuel",
      image: "src/assets/everaldo_junior.png",
      content: "A SASN Tecnologia transformou completamente nossa presença digital. O site ficou moderno, rápido e nossos doadores adoraram.",
      rating: 5,
    },
    {
      name: "Nelson Augusto",
      role: "Titular do Cartório",
      company: "Cartório do Único Ofício de Inhangapi do Pará",
      image: "src/assets/nelson_augusto.png",
      content: "Excelente atendimento e suporte técnico impecável. A equipe é muito profissional e sempre disponível para resolver qualquer problema rapidamente.",
      rating: 5,
    },
    {
      name: "Lucas Cruz",
      role: "Proprietário",
      company: "Comércio Regional",
      image: "src/assets/lucas_cruz.png",
      content: "O sistema de gestão desenvolvido pela SASN aumentou nossa produtividade em 40%. Recomendo para qualquer empresa que queira crescer com tecnologia.",
      rating: 5,
    },
  ];

  return (
    <section className="py-24 bg-muted/30 relative overflow-hidden">
      {/* Background */}
      <div className="absolute inset-0 bg-grid-pattern opacity-20" />
      
      <div className="container mx-auto px-4 relative z-10">
        {/* Header */}
        <div className="text-center max-w-3xl mx-auto mb-16">
          <span className="text-primary font-semibold text-sm tracking-wider uppercase mb-4 block">
            Depoimentos
          </span>
          <h2 className="text-3xl lg:text-5xl font-bold text-foreground mb-6">
            O que nossos{" "}
            <span className="text-primary">clientes</span> dizem
          </h2>
          <p className="text-muted-foreground text-lg">
            Veja o que empresas que já trabalharam conosco falam sobre nossos serviços.
          </p>
        </div>

        {/* Testimonials Grid */}
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
          {testimonials.map((testimonial, index) => (
            <div
              key={testimonial.name}
              className="relative p-8 rounded-2xl bg-card border border-border hover:border-primary/50 transition-all duration-500 animate-fade-in-up opacity-0 shadow-card group"
              style={{ animationDelay: `${index * 0.1}s` }}
            >
              {/* Quote icon */}
              <div className="absolute -top-4 -left-4 w-12 h-12 rounded-xl bg-primary flex items-center justify-center shadow-lg">
                <Quote className="w-6 h-6 text-primary-foreground" />
              </div>

              {/* Rating */}
              <div className="flex gap-1 mb-4 pt-2">
                {Array.from({ length: testimonial.rating }).map((_, i) => (
                  <Star key={i} className="w-5 h-5 fill-primary text-primary" />
                ))}
              </div>

              {/* Content */}
              <p className="text-muted-foreground mb-6 leading-relaxed">
                "{testimonial.content}"
              </p>

              {/* Author */}
              <div className="flex items-center gap-4">
                <img
                  src={testimonial.image}
                  alt={testimonial.name}
                  className="w-14 h-14 rounded-full object-cover border-2 border-primary/20"
                />
                <div>
                  <h4 className="font-semibold text-foreground">
                    {testimonial.name}
                  </h4>
                  <p className="text-sm text-muted-foreground">
                    {testimonial.role} • {testimonial.company}
                  </p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
};

export default Testimonials;
