import Sidebar from "./Sidebar";
import Navbar from "./Navbar";

export default function Layout({ children }) {
  return (
    <div className="layout">
      <Sidebar />
      <main className="main">
        <Navbar />
        <section className="content">{children}</section>
      </main>
    </div>
  );
}