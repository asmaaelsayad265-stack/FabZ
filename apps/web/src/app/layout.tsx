import './globals.css'
import type { Metadata } from 'next'

export const metadata: Metadata = {
    title: 'FabZ',
    description: 'Educational platform for robotics, coding, AI, electronics and fabrication',
}

export default function RootLayout({
    children,
}: Readonly<{ children: React.ReactNode }>) {
    return (
        <html lang="en" suppressHydrationWarning>
            <body className="min-h-screen bg-background text-foreground">
                {children}
            </body>
        </html>
    )
}

