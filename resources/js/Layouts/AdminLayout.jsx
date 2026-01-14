import React from 'react';
import { Link } from '@inertiajs/react';
import { LayoutDashboard, Users, Wifi, MessageSquareWarning, LogOut } from 'lucide-react';

export default function AdminLayout({ children, title }) {
  // Helper untuk cek active route (gunakan usePage().url di real app)
  const isActive = (route) => window.location.pathname.startsWith(route);

  return (
    <div className="flex min-h-screen bg-gray-50 font-sans text-gray-900">
      {/* Sidebar */}
      <aside className="w-64 bg-white border-r border-gray-100 hidden md:flex flex-col fixed h-full z-10">
        <div className="p-6 border-b border-gray-50">
          <div className="flex items-center space-x-2">
            <div className="bg-indigo-600 p-2 rounded-lg">
              <Wifi className="text-white" size={24} />
            </div>
            <span className="text-xl font-bold tracking-tight text-gray-900">NetAdmin</span>
          </div>
        </div>

        <nav className="flex-1 p-4 space-y-2">
          <Link href="/admin/dashboard" className="flex items-center space-x-3 px-4 py-3 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg">
            <LayoutDashboard size={20} /> <span className="font-medium">Dashboard</span>
          </Link>
          <Link href="/admin/packages" className="flex items-center space-x-3 px-4 py-3 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg">
            <Wifi size={20} /> <span className="font-medium">Paket Internet</span>
          </Link>
          <Link href="/admin/customers" className="flex items-center space-x-3 px-4 py-3 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg">
            <Users size={20} /> <span className="font-medium">Pelanggan</span>
          </Link>
          <Link href="/admin/complaints" className="flex items-center space-x-3 px-4 py-3 text-gray-500 hover:bg-indigo-50 hover:text-indigo-600 rounded-lg">
            <MessageSquareWarning size={20} /> <span className="font-medium">Pengaduan</span>
          </Link>
        </nav>

        <div className="p-4 border-t border-gray-50">
          <Link href="/logout" method="post" as="button" className="flex items-center space-x-3 px-4 py-3 text-gray-500 hover:text-red-600 transition w-full">
            <LogOut size={20} /> <span className="font-medium">Logout</span>
          </Link>
        </div>
      </aside>

      {/* Main Content */}
      <main className="flex-1 md:ml-64 p-6">
        <h1 className="text-2xl font-bold text-gray-800 mb-6">{title}</h1>
        {children}
      </main>
    </div>
  );
}
