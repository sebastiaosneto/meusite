import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import { HelmetProvider } from "react-helmet-async";
import ScrollToTop from "./components/ScrollToTop";
import Index from "./pages/Index";
import NotFound from "./pages/NotFound";
import SolucaoWebsites from "./pages/SolucaoWebsites";
import SolucaoSistemas from "./pages/SolucaoSistemas";
import SolucaoServidores from "./pages/SolucaoServidores";
import SolucaoSeguranca from "./pages/SolucaoSeguranca";
import SolucaoBancoDados from "./pages/SolucaoBancoDados";
import SolucaoSuporteTecnico from "./pages/SolucaoSuporteTecnico";
import SuporteCorporativo from "./pages/SuporteCorporativo";
import Projetos from "./pages/Projetos";

const queryClient = new QueryClient();

const App = () => (
  <HelmetProvider>
    <QueryClientProvider client={queryClient}>
      <TooltipProvider>
        <Toaster />
        <Sonner />
        <BrowserRouter>
          <ScrollToTop />
          <Routes>
            <Route path="/" element={<Index />} />
            <Route path="/solucoes/websites" element={<SolucaoWebsites />} />
            <Route path="/solucoes/sistemas" element={<SolucaoSistemas />} />
            <Route path="/solucoes/servidores" element={<SolucaoServidores />} />
            <Route path="/solucoes/seguranca" element={<SolucaoSeguranca />} />
            <Route path="/solucoes/banco-dados" element={<SolucaoBancoDados />} />
            <Route path="/solucoes/suporte-tecnico" element={<SolucaoSuporteTecnico />} />
            <Route path="/suporte-corporativo" element={<SuporteCorporativo />} />
            <Route path="/projetos" element={<Projetos />} />
            <Route path="*" element={<NotFound />} />
          </Routes>
        </BrowserRouter>
      </TooltipProvider>
    </QueryClientProvider>
  </HelmetProvider>
);

export default App;
