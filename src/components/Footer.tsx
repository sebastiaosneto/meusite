import { Facebook, Instagram, Linkedin, Twitter } from "lucide-react";
import logo from "@/assets/logo.png";

const Footer = () => {
  const currentYear = new Date().getFullYear();

  const links = {
    services: [
      { name: "Websites", href: "#solucoes" },
      { name: "Sistemas", href: "#solucoes" },
      { name: "Servidores", href: "#solucoes" },
      { name: "Suporte", href: "#solucoes" },
    ],
    company: [
      { name: "Sobre", href: "#sobre" },
      { name: "Projetos", href: "/projetos" },
      { name: "Contato", href: "#contato" },
    ],
  };

  const socials = [
    { icon: Facebook, href: "#", label: "Facebook" },
    { icon: Instagram, href: "#", label: "Instagram" },
    { icon: Linkedin, href: "#", label: "LinkedIn" },
    { icon: Twitter, href: "#", label: "Twitter" },
  ];

  return (
    <footer className="bg-foreground text-background">
      <div className="container mx-auto px-4 py-16">
        <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-12">
          {/* Brand */}
          <div className="lg:col-span-2">
            <a href="#inicio" className="flex items-center gap-3 mb-6">
              <img src={logo} alt="SASN Tecnologia" className="h-12 w-auto" />
            </a>
            <p className="text-background/70 max-w-md mb-6">
              Soluções completas em tecnologia da informação para impulsionar 
              sua empresa no mundo digital. Desenvolvimento de websites, sistemas 
              e consultoria em TI.
            </p>
            
            {/* Social links */}
            <div className="flex gap-3">
              {socials.map((social) => (
                <a
                  key={social.label}
                  href={social.href}
                  aria-label={social.label}
                  className="w-10 h-10 rounded-lg bg-background/10 flex items-center justify-center text-background/70 hover:bg-primary hover:text-primary-foreground transition-all duration-300"
                >
                  <social.icon className="w-5 h-5" />
                </a>
              ))}
            </div>
          </div>

          {/* Services links */}
          <div>
            <h4 className="text-background font-semibold mb-4">Serviços</h4>
            <ul className="space-y-3">
              {links.services.map((link) => (
                <li key={link.name}>
                  <a
                    href={link.href}
                    className="text-background/70 hover:text-primary transition-colors duration-300"
                  >
                    {link.name}
                  </a>
                </li>
              ))}
            </ul>
          </div>

          {/* Company links */}
          <div>
            <h4 className="text-background font-semibold mb-4">Empresa</h4>
            <ul className="space-y-3">
              {links.company.map((link) => (
                <li key={link.name}>
                  <a
                    href={link.href}
                    className="text-background/70 hover:text-primary transition-colors duration-300"
                  >
                    {link.name}
                  </a>
                </li>
              ))}
            </ul>
          </div>
        </div>

        {/* Bottom bar */}
        <div className="border-t border-background/20 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
          <p className="text-background/60 text-sm">
            © {currentYear} SASN Tecnologia. Todos os direitos reservados.
          </p>
          <p className="text-background/60 text-sm">
            Desenvolvido com ❤️ por SASN Tech
          </p>
        </div>
      </div>
    </footer>
  );
};

export default Footer;