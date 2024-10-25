import Head from 'next/head';
import Header from './components/Header';
import Footer from './components/Footer';
import './style.css';

export default function Home() {
    return (
        <>
            <Head>
                <title>Портфолио веб-разработчика Lexa2005</title>
                <meta name="description" content="Портфолио веб-разработчика" />
            </Head>
            <div className={'background-image'}></div>
            <div className={'container'}>
                <Header />
                <main>
                    <section id="about" className={'banner'}>
                        <img src="/images/IMG_20241011_110246_391.jpg" alt="Lexa2005" className={'profileImage'} />
                        <h2>Обо мне</h2>
                        <p>Привет! Я Lexa2005, веб-разработчик с опытом в создании современных и функциональных веб-сайтов. Я люблю решать сложные задачи и создавать удобные интерфейсы.</p>
                    </section>
                </main>
                <Footer />
            </div>
        </>
    );
}