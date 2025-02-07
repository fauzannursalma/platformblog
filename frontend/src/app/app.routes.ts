import { Routes } from '@angular/router';
import { LoginComponent } from './components/auth/login/login.component';
import { RegisterComponent } from './components/auth/register/register.component';
import { PostListComponent } from './components/posts/post-list/post-list.component';
import { PostDetailComponent } from './components/posts/post-detail/post-detail.component';
import { PostFormComponent } from './components/posts/post-form/post-form.component';
import { AuthGuard } from './guards/auth.guard';

export const routes: Routes = [
  { path: '', component: PostListComponent },
  { path: 'login', component: LoginComponent },
  { path: 'register', component: RegisterComponent },
  { path: 'posts/new', component: PostFormComponent, canActivate: [AuthGuard] },
  { path: 'posts/:id', component: PostDetailComponent },
  {
    path: 'posts/:id/edit',
    component: PostFormComponent,
    canActivate: [AuthGuard],
  },
];
