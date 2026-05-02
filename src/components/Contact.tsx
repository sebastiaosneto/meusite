import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Mail, Phone, MapPin, Send, Clock } from "lucide-react";
import { useState } from "react";
import { useToast } from "@/hooks/use-toast";
import { Link } from "react-router-dom";

const Contact = () => {
  const { toast } = useToast();
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    phone: "",
    message: "",
  });

  const whatsappNumber = "5594991534873";
  const whatsappMessage = "Olá! Gostaria de saber mais sobre os serviços da SASN Tecnologia.";
  const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(whatsappMessage)}`;
  const contactEmail = import.meta.env.VITE_CONTACT_EMAIL || "contato@sasntecnologia.com.br";
  const formSubmitEndpoint = `https://formsubmit.co/ajax/${contactEmail}`;

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsSubmitting(true);

    const formattedMessage = [
      "Olá! Gostaria de solicitar um orçamento.",
      "",
      `Nome: ${formData.name}`,
      `E-mail: ${formData.email}`,
      `Telefone: ${formData.phone || "Não informado"}`,
      `Mensagem: ${formData.message}`,
    ].join("\n");

    const dynamicWhatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(formattedMessage)}`;

    try {
      const response = await fetch(formSubmitEndpoint, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Accept: "application/json",
        },
        body: JSON.stringify({
          name: formData.name,
          email: formData.email,
          phone: formData.phone || "Não informado",
          message: formData.message,
          _subject: "Novo contato - Site SASN Tecnologia",
          _template: "table",
          _captcha: "false",
        }),
      });

      if (!response.ok) {
        throw new Error("Falha no envio por e-mail");
      }

      toast({
        title: "Mensagem enviada por e-mail!",
        description: `Recebemos seu contato em ${contactEmail} e responderemos em breve.`,
      });
      setFormData({ name: "", email: "", phone: "", message: "" });
    } catch {
      const whatsappWindow = window.open(dynamicWhatsappUrl, "_blank");
      if (!whatsappWindow) {
        window.location.href = dynamicWhatsappUrl;
      }

      toast({
        title: "E-mail indisponível no momento",
        description: "Abrimos o WhatsApp para você concluir o contato.",
      });
      setFormData({ name: "", email: "", phone: "", message: "" });
    } finally {
      setIsSubmitting(false);
    }
  };

  const contactInfo = [
    {
      icon: Mail,
      label: "E-mail",
      value: "contato@sasntecnologia.com.br",
      href: "mailto:contato@sasntecnologia.com.br",
    },
    {
      icon: Phone,
      label: "WhatsApp",
      value: "(94) 99153-4873",
      href: whatsappUrl,
    },
    {
      icon: MapPin,
      label: "Localização",
      value: "Castanhal/PA",
      href: "https://maps.google.com/?q=Castanhal,PA",
    },
    {
      icon: Clock,
      label: "Horário",
      value: "Seg - Sex: 8h às 18h",
      href: "#",
    },
  ];

  return (
    <section id="contato" className="py-24 bg-muted/50 relative overflow-hidden">
      {/* Background */}
      <div className="absolute inset-0 bg-grid-pattern opacity-30" />
      <div className="absolute top-0 left-0 w-1/2 h-full bg-gradient-to-r from-primary/5 to-transparent" />

      <div className="container mx-auto px-4 relative z-10">
        {/* Header */}
        <div className="text-center max-w-3xl mx-auto mb-16">
          <span className="text-primary font-semibold text-sm tracking-wider uppercase mb-4 block">
            Contato
          </span>
          <h2 className="text-3xl lg:text-5xl font-bold text-foreground mb-6">
            Vamos conversar sobre seu{" "}
            <span className="text-primary">projeto</span>?
          </h2>
          <p className="text-muted-foreground text-lg">
            Entre em contato conosco e descubra como podemos ajudar sua empresa 
            a crescer com tecnologia.
          </p>
        </div>

        <div className="grid lg:grid-cols-5 gap-12">
          {/* Contact Info */}
          <div className="lg:col-span-2 space-y-6">
            {contactInfo.map((info, index) => (
              <a
                key={info.label}
                href={info.href}
                className="flex items-start gap-4 p-5 rounded-xl bg-card border border-border hover:border-primary/50 transition-all duration-300 group animate-fade-in-up opacity-0 shadow-card"
                style={{ animationDelay: `${index * 0.1}s` }}
              >
                <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center group-hover:bg-primary transition-colors">
                  <info.icon className="w-6 h-6 text-primary group-hover:text-primary-foreground transition-colors" />
                </div>
                <div>
                  <span className="text-sm text-muted-foreground">{info.label}</span>
                  <p className="text-foreground font-medium">{info.value}</p>
                </div>
              </a>
            ))}

            {/* Support banner */}
            <div className="p-6 rounded-xl bg-foreground text-background">
              <h3 className="text-lg font-bold mb-2">
                Suporte Corporativo
              </h3>
              <p className="text-background/70 text-sm mb-4">
                Atendimento personalizado para empresas que precisam de suporte 
                técnico contínuo e soluções sob demanda.
              </p>
              <Link to="/suporte-corporativo">
                <Button variant="secondary" size="sm" className="bg-background text-foreground hover:bg-background/90">
                  Saiba mais
                </Button>
              </Link>
            </div>
          </div>

          {/* Contact Form */}
          <div className="lg:col-span-3">
            <form
              onSubmit={handleSubmit}
              className="p-8 rounded-2xl bg-card border border-border animate-fade-in-up opacity-0 animation-delay-200 shadow-card"
            >
              <h3 className="text-xl font-bold text-foreground mb-6">
                Envie sua mensagem
              </h3>

              <div className="grid sm:grid-cols-2 gap-4 mb-4">
                <div>
                  <label className="text-sm text-muted-foreground mb-2 block">
                    Nome completo
                  </label>
                  <Input
                    placeholder="Seu nome"
                    value={formData.name}
                    onChange={(e) =>
                      setFormData({ ...formData, name: e.target.value })
                    }
                    required
                    className="bg-muted/50 border-border focus:border-primary"
                  />
                </div>
                <div>
                  <label className="text-sm text-muted-foreground mb-2 block">
                    E-mail
                  </label>
                  <Input
                    type="email"
                    placeholder="seu@email.com"
                    value={formData.email}
                    onChange={(e) =>
                      setFormData({ ...formData, email: e.target.value })
                    }
                    required
                    className="bg-muted/50 border-border focus:border-primary"
                  />
                </div>
              </div>

              <div className="mb-4">
                <label className="text-sm text-muted-foreground mb-2 block">
                  Telefone
                </label>
                <Input
                  type="tel"
                  placeholder="(XX) XXXXX-XXXX"
                  value={formData.phone}
                  onChange={(e) =>
                    setFormData({ ...formData, phone: e.target.value })
                  }
                  className="bg-muted/50 border-border focus:border-primary"
                />
              </div>

              <div className="mb-6">
                <label className="text-sm text-muted-foreground mb-2 block">
                  Mensagem
                </label>
                <Textarea
                  placeholder="Descreva seu projeto ou dúvida..."
                  rows={5}
                  value={formData.message}
                  onChange={(e) =>
                    setFormData({ ...formData, message: e.target.value })
                  }
                  required
                  className="bg-muted/50 border-border focus:border-primary resize-none"
                />
              </div>

              <Button variant="hero" size="lg" className="w-full" disabled={isSubmitting}>
                {isSubmitting ? "Enviando..." : "Enviar mensagem"}
                <Send className="w-4 h-4 ml-2" />
              </Button>
            </form>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Contact;