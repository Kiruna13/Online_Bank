package com.example.banqueenligne

import android.os.Bundle
import android.view.Window
import androidx.appcompat.app.AppCompatActivity

class Accounts : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        requestWindowFeature(Window.FEATURE_NO_TITLE)
        supportActionBar?.hide()
        setContentView(R.layout.activity_accounts)
    }
}