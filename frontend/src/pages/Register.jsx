import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { Archive, Lock, Mail, User } from "lucide-react";
import api from "../services/api";

export default function Register() {
  const navigate = useNavigate();

  const [form, setForm] = useState({
    nom: "",
    prenom: "",
    email: "",
    mot_de_passe: "",
  });

  const [erreur, setErreur] = useState("");

  const handleChange = (e) => {
    setForm({
      ...form,
      [e.target.name]: e.target.value,
    });
  };

  const inscription = async (e) => {
    e.preventDefault();
    setErreur("");

    try {
      const res = await api.post("/register", form);

      localStorage.setItem("token", res.data.token);
      localStorage.setItem("utilisateur", JSON.stringify(res.data.utilisateur));

      navigate("/documents/ajouter");
    } catch (error) {
      setErreur(error.response?.data?.message || "Erreur d'inscription");
    }
  };

  return (
    <div className="login-page">
      <form className="login-card" onSubmit={inscription}>
        <div className="login-logo">
          <Archive size={30} />
        </div>

        <h1>Inscription</h1>
        <p>Créer un compte gestionnaire documentaire</p>

        {erreur && <div className="error-box">{erreur}</div>}

        <div className="input-group">
          <User size={18} />
          <input
            name="prenom"
            placeholder="Prénom"
            value={form.prenom}
            onChange={handleChange}
          />
        </div>

        <div className="input-group">
          <User size={18} />
          <input
            name="nom"
            placeholder="Nom"
            value={form.nom}
            onChange={handleChange}
          />
        </div>

        <div className="input-group">
          <Mail size={18} />
          <input
            name="email"
            type="email"
            placeholder="Adresse email"
            value={form.email}
            onChange={handleChange}
          />
        </div>

        <div className="input-group">
          <Lock size={18} />
          <input
            name="mot_de_passe"
            type="password"
            placeholder="Mot de passe"
            value={form.mot_de_passe}
            onChange={handleChange}
          />
        </div>

        <button className="login-btn">
          Créer le compte
        </button>

        <p style={{ marginTop: 18 }}>
          Déjà un compte ? <Link to="/">Se connecter</Link>
        </p>
      </form>
    </div>
  );
}