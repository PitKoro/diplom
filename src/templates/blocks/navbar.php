<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="./main.php">KorolevDiplom</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <ul class="navbar-nav">
                <li class="nav-item px-1">
                    <a class="nav-link" aria-current="page" href="./main.php">Главная</a>
                </li>

                <li class="nav-item px-1">
                    <a class="nav-link" href="./myProjects.php">Мои проекты</a>
                </li>

                <li class="nav-item px-1">
                    <a class="nav-link" href="./profile.php">Мой профиль</a>
                </li>
                <? if ($_SESSION['user']['status']=='10'): ?>
                    <li class="nav-item px-1">
                        <a class="nav-link" href="./myUsers.php">Пользователи</a>
                    </li>
                <? endif; ?>

                <!-- <li class="nav-item dropdown px-1">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown-menu-profile" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Профиль
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdown-menu-profile">
                        <li class="dropdown-li"><a class="dropdown-item" href="./profile.php">Мой профиль</a></li>
                        <li class="dropdown-li"><a class="dropdown-item" href="./myProjects.php">Мои проекты</a></li>
                        <? if ($_SESSION['user']['status']=='10'): ?>
                            <li class="dropdown-li"><a class="dropdown-item" href="./myUsers.php">Пользователи</a></li>
                        <? endif; ?>
                        
                    </ul>
                </li> -->
                <? if ($_SESSION['user']['status']=='10'): ?>
                    <li class="nav-item dropdown px-1">
                        <a class="nav-link dropdown-toggle" href="#" id="dropdown-menu-create" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Добавить
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdown-menu-create">
                            <li class="dropdown-li"><a class="dropdown-item" href="./registration.php">Новый пользователь</a></li>
                            <li class="dropdown-li"><a class="dropdown-item" href="./addProject.php">Новый проект</a></li>
                            
                        </ul>
                    </li>
                <? endif; ?>
            </ul>
            
            <ul class="ms-auto navbar-nav">
                <li class="nav-item px-1">
                    <a class="nav-link" href="../php/logout.php">Выйти</a>   
                </li>
            </ul>

        </div>
    </div>
</nav>