import { useState } from "react";
import Layout from "../components/Layout";
import api from "../services/api";

export default function Historique() {
  const [idDocument, setIdDocument] = useState("");
  const [historique, setHistorique] = useState(null);
  const [message, setMessage] = useState("");

  const rechercherHistorique = async () => {
    setMessage("");
    setHistorique(null);

    try {
      const res = await api.get(`/historique/documents/${idDocument}`);
      setHistorique(res.data.historique);
    } catch (error) {
      setMessage(error.response?.data?.message || "Erreur de recherche");
    }
  };

  return (
    <Layout>
      <div className="page-header">
        <h1>Historique</h1>
        <p>Consulter la traçabilité complète d’un document</p>
      </div>

      <div className="toolbar">
        <input
          className="history-input"
          placeholder="ID document ex: 1"
          value={idDocument}
          onChange={(e) => setIdDocument(e.target.value)}
        />

        <button onClick={rechercherHistorique}>
          Rechercher
        </button>
      </div>

      {message && <div className="error-box">{message}</div>}

      {historique && (
        <div className="panel">
          <h3>{historique.titre}</h3>
          <p><strong>Référence :</strong> {historique.reference}</p>
          <p><strong>Statut :</strong> {historique.statut}</p>
          <p><strong>Catégorie :</strong> {historique.categorie?.nomCategorie}</p>
          <p><strong>Dossier :</strong> {historique.dossier?.nomDossier}</p>

          <h3>Versions</h3>
          {historique.versions?.map((v) => (
            <div className="list-row" key={v.idVersion}>
              <span>Version {v.numeroVersion}</span>
              <small>{v.commentaire}</small>
            </div>
          ))}

          <h3>Validations</h3>
          {historique.validations?.map((v) => (
            <div className="list-row" key={v.idValidation}>
              <span>{v.decision}</span>
              <small>{v.commentaire}</small>
            </div>
          ))}

          <h3>Archivage</h3>
          {historique.archivage ? (
            <p>
              {historique.archivage.emplacementArchive} —{" "}
              {historique.archivage.motifArchivage}
            </p>
          ) : (
            <p>Non archivé</p>
          )}
        </div>
      )}
    </Layout>
  );
}