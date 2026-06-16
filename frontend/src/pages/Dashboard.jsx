import { useEffect, useState } from "react";
import { Archive, CheckCircle, Clock, FileText } from "lucide-react";
import Layout from "../components/Layout";
import api from "../services/api";

export default function Dashboard() {
  const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));
  const [stats, setStats] = useState(null);

  useEffect(() => {
    api
      .get("/dashboard/stats")
      .then((response) => setStats(response.data))
      .catch((error) => console.error(error));
  }, []);

  return (
    <Layout>
      <div className="page-header">
        <h1>Dashboard</h1>
        <p>
          Bienvenue {utilisateur?.prenom} {utilisateur?.nom}
        </p>
      </div>

      <div className="stats-grid">
        <div className="stat-card">
          <FileText />
          <div>
            <h3>Total documents</h3>
            <h2>{stats?.totalDocuments ?? "..."}</h2>
          </div>
        </div>

        <div className="stat-card">
          <Clock />
          <div>
            <h3>En attente</h3>
            <h2>{stats?.documentsSoumis ?? "..."}</h2>
          </div>
        </div>

        <div className="stat-card">
          <CheckCircle />
          <div>
            <h3>Validés</h3>
            <h2>{stats?.documentsValides ?? "..."}</h2>
          </div>
        </div>

        <div className="stat-card">
          <Archive />
          <div>
            <h3>Archivés</h3>
            <h2>{stats?.documentsArchives ?? "..."}</h2>
          </div>
        </div>
      </div>
<div className="dashboard-grid">
  <div className="panel">
    <h3>Documents récents</h3>

    {stats?.documentsRecents?.length > 0 ? (
      stats.documentsRecents.map((doc) => (
        <div className="list-row" key={doc.idDocument}>
          <span>{doc.titre}</span>
          <span className={`status-badge status-${doc.statut}`}>
            {doc.statut}
          </span>
        </div>
      ))
    ) : (
      <p>Aucun document récent</p>
    )}
  </div>

  <div className="panel">
    <h3>Activités récentes</h3>

    {stats?.activitesRecentes?.length > 0 ? (
      stats.activitesRecentes.map((act) => (
        <div className="list-row" key={act.idAudit}>
          <span>{act.action}</span>
          <small>{act.dateAction}</small>
        </div>
      ))
    ) : (
      <p>Aucune activité récente</p>
    )}
  </div>
</div>
      <div className="panel">
        <h3>Documents récents</h3>

        {stats?.documentsRecents?.map((doc) => (
          <div className="list-row" key={doc.idDocument}>
            <span>{doc.titre}</span>
            <strong>{doc.statut}</strong>
          </div>
        ))}
      </div>
    </Layout>
  );
}