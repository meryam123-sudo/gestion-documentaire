import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { Archive, Lock, Mail } from "lucide-react";
import api from "../services/api";

export default function Login() {
  const [email, setEmail] = useState("ahmed@gmail.com");
  const [motDePasse, setMotDePasse] = useState("123456");
  const [erreur, setErreur] = useState("");

  const navigate = useNavigate();

  const handleLogin = async (e) => {
    e.preventDefault();

    setErreur("");

    try {
      const response = await api.post("/login", {
        email,
        mot_de_passe: motDePasse,
      });

      localStorage.setItem("token", response.data.token);

      localStorage.setItem(
        "utilisateur",
        JSON.stringify(response.data.utilisateur)
      );

      const role = response.data.utilisateur.role.nomRole;

      console.log("Rôle connecté :", role);

      if (role === "Archiviste") {
        navigate("/archivage");
      } else if (role === "Gestionnaire documentaire") {
        navigate("/documents/ajouter");
      } else {
        navigate("/dashboard");
      }
    } catch (error) {
      setErreur(
        error.response?.data?.message || "Erreur de connexion"
      );
    }
  };

  return (
    <div className="login-page">
      <form className="login-card" onSubmit={handleLogin}>
        <div className="login-logo">
          <Archive size={30} />
        </div>

        <h1>ARCHIVAGE</h1>

        <p>Plateforme de gestion documentaire</p>

        {erreur && (
          <div className="error-box">
            {erreur}
          </div>
        )}

        <div className="input-group">
          <Mail size={18} />

          <input
            type="email"
            placeholder="Adresse email"
            value={email}
            onChange={(e) => setEmail(e.target.value)}
            required
          />
        </div>

        <div className="input-group">
          <Lock size={18} />

          <input
            type="password"
            placeholder="Mot de passe"
            value={motDePasse}
            onChange={(e) => setMotDePasse(e.target.value)}
            required
          />
        </div>

        <button type="submit" className="login-btn">
          Se connecter
        </button>

        <p style={{ marginTop: "20px", textAlign: "center" }}>
          Pas encore de compte ?{" "}
          <Link to="/register">
            Créer un compte
          </Link>
        </p>
      </form>
    </div>
  );
}
