import React from 'react';
import './App.css';
import VideoCard from './VideoCard';

function App() {
    return (
        <div className="video-grid">
            <VideoCard
                thumbnailUrl="https://via.placeholder.com/320x180"
                title="Заголовок видео 1"
                channel="Канал 1"
                views="100 тыс. просмотров"
            />
            <VideoCard
                thumbnailUrl="https://via.placeholder.com/320x180"
                title="Заголовок видео 2"
                channel="Канал 2"
                views="50 тыс. просмотров"
            />
            <VideoCard
                thumbnailUrl="https://via.placeholder.com/320x180"
                title="Заголовок видео 3"
                channel="Канал 3"
                views="200 тыс. просмотров"
            />
            {/* Добавьте больше видео-карточек по аналогии */}
        </div>
    );
}

export default App;