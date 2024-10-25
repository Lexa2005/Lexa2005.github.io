import Link from 'next/link';

export default function Header() {
    return (
        <header>
            <h1>Портфолио веб-разработчика Lexa2005</h1>
            <nav>
                <ul>
                    <li><Link href="/">Обо мне</Link></li>
                    <li><Link href="/education">Обучение</Link></li>
                    <li><Link href="/projects">Проекты</Link></li>
                    <li><Link href="/skills">Навыки</Link></li>
                    <li><Link href="/contact">Контакты</Link></li>
                </ul>
            </nav>
        </header>
    );
}