import {
  Archive,
  BarChart3,
  FileText,
  FilePlus,
  CheckSquare,
  Bell,
  Users,
  LogOut,
  History,
  ShieldCheck,
} from "lucide-react";

import { NavLink, useNavigate } from "react-router-dom";

export default function Sidebar() {
  const navigate = useNavigate();

  const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));

  const role = utilisateur?.role?.nomRole;

  const logout = () => {
    localStorage.removeItem("token");
    localStorage.removeItem("utilisateur");

    navigate("/");
  };

  return (
    <aside className="sidebar">
      <div className="brand">
        <Archive size={38} />

        <div>
          <h2>ARCHIVAGE</h2>
          <p>Gestion documentaire</p>
        </div>
      </div>

      <nav>
        {role === "Administrateur Principal" && (
          <>
            <NavLink to="/dashboard">
              <BarChart3 size={18} />
              Dashboard
            </NavLink>

            <NavLink to="/documents">
              <FileText size={18} />
              Documents
            </NavLink>

            <NavLink to="/documents/ajouter">
              <FilePlus size={18} />
              Ajouter document
            </NavLink>

            <NavLink to="/validations">
              <CheckSquare size={18} />
              Validations
            </NavLink>

            <NavLink to="/archivage">
              <Archive size={18} />
              Archivage
            </NavLink>

            <NavLink to="/historique">
              <History size={18} />
              Historique
            </NavLink>

            <NavLink to="/notifications">
              <Bell size={18} />
              Notifications
            </NavLink>

            <NavLink to="/utilisateurs">
              <Users size={18} />
              Utilisateurs
            </NavLink>

            <NavLink to="/roles">
              <ShieldCheck size={18} />
              Rôles
            </NavLink>
          </>
        )}

        {role === "Gestionnaire documentaire" && (
          <>
            <NavLink to="/dashboard">
              <BarChart3 size={18} />
              Dashboard
            </NavLink>

            <NavLink to="/documents">
              <FileText size={18} />
              Documents
            </NavLink>

            <NavLink to="/documents/ajouter">
              <FilePlus size={18} />
              Ajouter document
            </NavLink>

            <NavLink to="/historique">
              <History size={18} />
              Historique
            </NavLink>

            <NavLink to="/notifications">
              <Bell size={18} />
              Notifications
            </NavLink>
          </>
        )}

        {role === "Archiviste" && (
          <>
            <NavLink to="/dashboard">
              <BarChart3 size={18} />
              Dashboard
            </NavLink>

            <NavLink to="/documents">
              <FileText size={18} />
              Documents
            </NavLink>

            <NavLink to="/archivage">
              <Archive size={18} />
              Archivage
            </NavLink>

            <NavLink to="/historique">
              <History size={18} />
              Historique
            </NavLink>

            <NavLink to="/notifications">
              <Bell size={18} />
              Notifications
            </NavLink>
          </>
        )}

        {role === "Consultant autorisé" && (
          <>
            <NavLink to="/dashboard">
              <BarChart3 size={18} />
              Dashboard
            </NavLink>

            <NavLink to="/documents">
              <FileText size={18} />
              Documents
            </NavLink>

            <NavLink to="/notifications">
              <Bell size={18} />
              Notifications
            </NavLink>
          </>
        )}

        {role === "Auditeur" && (
          <>
            <NavLink to="/dashboard">
              <BarChart3 size={18} />
              Dashboard
            </NavLink>

            <NavLink to="/historique">
              <History size={18} />
              Historique
            </NavLink>
          </>
        )}

        {role === "Responsable de validation" && (
          <>
            <NavLink to="/dashboard">
              <BarChart3 size={18} />
              Dashboard
            </NavLink>

            <NavLink to="/validations">
              <CheckSquare size={18} />
              Validations
            </NavLink>

            <NavLink to="/historique">
              <History size={18} />
              Historique
            </NavLink>

            <NavLink to="/notifications">
              <Bell size={18} />
              Notifications
            </NavLink>
          </>
        )}
      </nav>

      <div className="sidebar-user">
        <strong>
          {utilisateur?.prenom} {utilisateur?.nom}
        </strong>

        <small>{role}</small>

        <button onClick={logout}>
          <LogOut size={16} />
          Déconnexion
        </button>
      </div>
    </aside>
  );
}