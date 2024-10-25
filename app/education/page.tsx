
import Header from '../components/Header';
import Footer from '../components/Footer';
import './style.css';

export default function Home() {
    return (
        <>
            <div className={'background-image'}></div>
            <div className={'container'}>
                <Header />
                <main>
                    <section id="about" className={'banner'}>
                        ТЕФЛЕЛИ!!!!!
                    </section>
                </main>
                <Footer />
            </div>
        </>
    );
}